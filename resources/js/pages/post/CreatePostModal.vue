<template>
    <div class="card flex justify-center">
        <Toast />
        <Button v-if="!isEditMode"  :label="props" @click="visible = true" severity="success" variant="link" />
        <Dialog v-model:visible="visible" modal :header="isEditMode ? 'Edit Post' : 'Input Youtube URL'" :style="{ width: '50rem' }" position="center">
            <Card>
                <template #content>
                    <span class="text-surface-500 dark:text-surface-400 block mb-8">Update your information.</span>
                        <div class="flex items-center gap-4 mb-4" >
                            <label for="username" class="font-semibold w-24">URLS YT</label>
                            <InputText id="username" class="flex-auto" fluid autocomplete="off" v-model="youtubeURL" />
                            <Message v-if="errors?.youtube_url" severity="error" size="small" variant="simple">{{
                                errors?.youtube_url[0] }}</Message>
                        </div>
                        <div class="flex justify-end gap-2">
                            <Button type="button" label="Clear" severity="secondary" @click="clear" icon="pi pi-trash"></Button>
                            <Button type="submit" label="Search" @click="onSubmit" icon="pi pi-search" severity="success"
                                variant="outlined"></Button>
                        </div>
                    <div class="mt-4" v-if="data?.youtube_video_data">
                        <Tag icon="pi pi-video" severity="success" :value="'Public AT: '+ data?.youtube_video_data?.items[0]?.snippet?.publishedAt"></Tag>
                        <Tag icon="pi pi-user" class="ml-2" severity="success" :value="'Channel ID: '+ data?.youtube_video_data?.items[0]?.snippet?.channelId"></Tag>
                    </div>
                    <div class="mt-2" v-if="data?.youtube_video_data">
                        <label for="username">Thumbnail</label>
                        <img :src="data?.youtube_video_data?.items[0]?.snippet?.thumbnails?.maxres?.url" alt="Thumbnail"
                            class="w-full h-auto rounded-lg shadow-md mb-4">
                    </div>
                </template>
                <template #footer v-if="data?.youtube_video_data">
                    <form @submit.prevent="saveData()">
                        <div class="flex flex-col gap-2">
                            <label for="username">Channel Name</label>
                            <InputText type="text" v-model="channelName" class="w-full" />
                            <label for="username">Input Tags</label>
                            <MultiSelect v-model="selectedTags" :options="data?.youtube_video_data?.items[0]?.snippet?.tags"
                                filter :optionLabel="name" placeholder="Select Tags" :maxSelectedLabels="100"
                                class="w-full" />
                            <label for="username">Title</label>
                            <InputText type="text" v-model="title" class="w-full" />
                            <label for="username">Description</label> 
                            <Editor v-model="descriptionHtml" editorStyle="height: 320px">
                                <template v-slot:toolbar>
                                    <span class="ql-formats">
                                        <button v-tooltip.bottom="'Bold'" class="ql-bold"></button>
                                        <button v-tooltip.bottom="'Italic'" class="ql-italic"></button>
                                        <button v-tooltip.bottom="'Underline'" class="ql-underline"></button>
                                    </span>
                                </template>
                            </Editor>

                            <div class="flex gap-2 mt-4"> 
                                <Button label="Cancel" class="flex-1" severity="secondary" @click="visible = false" icon="pi pi-times" variant="outlined" />
                                <Button :label="isEditMode ? 'Update' : 'Save'" class="flex-1" type="submit" icon="pi pi-save" severity="success" variant="outlined" />
                            </div>
                        </div>
                    </form>
                </template>
            </Card>
        </Dialog>
    </div>
</template>

<script>
import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import InputText from 'primevue/inputtext'
import Message from 'primevue/message'
import Editor from 'primevue/editor';
import axios from 'axios';
import Badge from 'primevue/badge';
import Textarea from 'primevue/textarea';
import Panel from 'primevue/panel';
import Card from 'primevue/card';
import MultiSelect from 'primevue/multiselect';
import Tag from 'primevue/tag';
import Toast from 'primevue/toast';
import Tooltip from 'primevue/tooltip';
export default {
    props: {
        props: {
            type: String,
            default: () => ({})
        }
    },
    directives: {
        tooltip: Tooltip
    },
    components: {
        Button,Dialog,InputText,Message,Editor,Badge,Textarea,Panel,Card,MultiSelect,Tag,Toast
    },
    data() {
        return {
            visible: false,
            youtubeURL: '',
            errors: {},
            data: {},
            description: '',
            title: '',
            selectedTags: {},
            name: '',
            channelName: '',
            publishedAt: '',
            isEditMode: false,
            editingPostId: null
        };
    },
    computed: {
        descriptionHtml() {
            return this.description
                .replace(/\n/g, '<br>')
                .replace(
                    /(https?:\/\/[^\s]+)/g,
                    '<a href="$1" target="_blank" class="yt-link">$1</a>'
                )
        }
    },
    watch: {
        visible(newVal) {
            if (!newVal) {
                this.clear();
            }
        }
    },
    methods: {
        openEditModal(post) {
            this.isEditMode = true;
            this.editingPostId = post.id;
            this.data = {
                youtube_image_url: post.youtube_image_url
            };
            this.youtubeURL = post.youtube_video_url;
            this.title = post.title;
            this.description = post.description;
            this.channelName = post.channel_name;
            this.selectedTags = this.parseTags(post.tags);
            this.data = {
                youtube_video_data: {
                    items: [{
                        snippet: {
                            title: post.title,
                            description: post.description,
                            channelTitle: post.channel_name,
                            channelId: post.channel_id,
                            publishedAt: post.published_at,
                            thumbnails: {
                                maxres: {
                                    url: `https://img.youtube.com/vi/${post.video_id}/maxresdefault.jpg`
                                }
                            },
                            tags: this.parseTags(post.tags)
                        },
                        id: post.video_id
                    }]
                }
            };
            this.visible = true;
        },
        parseTags(tags) {
            if (typeof tags === 'string') {
                try {
                    return JSON.parse(tags);
                } catch {
                    return [];
                }
            }
            return Array.isArray(tags) ? tags : [];
        },
        onSubmit() {
            try {
                axios.post(`/api/admin/youtube_detail`, {
                    youtube_url: this.youtubeURL, 
                }).then((res) => {
                    this.errors = {},
                    this.data = res.data.data; 
                    this.description = this.data.youtube_video_data.items[0].snippet.description,
                    this.title = this.data.youtube_video_data.items[0].snippet.title
                    this.channelName = this.data.youtube_video_data.items[0].snippet.channelTitle
                }).catch((err) => {
                    this.errors = err.response.data.errors;
                });

            } catch (error) {
                console.log(error)
            }
        },
        saveData(){
            const formData = {
                youtube_video_url: this.youtubeURL,
                youtube_image_url: this.data.youtube_video_data.items[0].snippet.thumbnails.maxres.url,
                title: this.title,
                description: this.description,
                tags: JSON.stringify(this.selectedTags),
                channel_name: this.channelName,
                channel_id: this.data.youtube_video_data.items[0].snippet.channelId,    
                video_id: this.data.youtube_video_data.items[0].id,
                published_at: this.data.youtube_video_data.items[0].snippet.publishedAt,
                categoryId: this.data.youtube_video_data.items[0].snippet.categoryId
                
            } 
            const url = this.isEditMode ? `/api/admin/posts/save_youtube_post/${this.editingPostId}` : '/api/admin/posts/save_youtube_post';
            const method = this.isEditMode ? 'put' : 'post';
             
            try {
                axios[method](url, formData).then((res) => {
                    if(res){
                        const message = this.isEditMode ? 'Post Updated Successfully' : 'Post Created Successfully';
                        this.$toast.add({severity:'success', summary: 'Success', detail: message, life: 3000});
                        this.$emit('postCreated');
                        this.clear();
                        this.visible = false
                    }
                }).catch((err) => {
                    this.$toast.add({ severity: 'error', summary: 'Error', detail: err.response.data.errors.youtube_video_url?.[0], life: 3000 });
                });
            } catch (error) {
                console.log(error)
            }
        },
        clear() { 
            this.youtubeURL = '',
            this.errors = {},
            this.data = {},
            this.description = '',
            this.title = '',
            this.selectedTags = {},
            this.name = '',
            this.channelName = '',
            this.isEditMode = false,
            this.editingPostId = null
        },

    }
};
</script>
