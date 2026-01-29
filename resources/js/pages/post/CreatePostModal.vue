<template>
    <div class="card flex justify-center">
        <Toast />
        <Button type="button" :label="props" icon="pi pi-plus-circle" @click="visible = true" severity="success"
            variant="outlined" />
        <Dialog v-model:visible="visible" modal header="Input Youtube URL" :style="{ width: '50rem' }" position="top">
            <Card>
                <template #content>
                    <span class="text-surface-500 dark:text-surface-400 block mb-8">Update your information.</span>
                        <div class="flex items-center gap-4 mb-4">
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
                            <label for="username">Input Description</label>
                            <InputText type="text" v-model="title" class="w-full" />
                            <Button label="Save" class="w-full mt-4" type="submit" icon="pi pi-save" security="success" variant="outlined" />
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
export default {
    props: {
        props: {
            type: String,
            default: () => ({})
        }
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
            publishedAt: ''
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
    methods: {
        onSubmit() {
            try {
                axios.post(`/api/youtube_detail`, {
                    youtube_url: this.youtubeURL
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
                console.log(errors.response)
            }
        },
        saveData(){
            const formData = new FormData();
            formData.append('youtube_video_url', this.youtubeURL);
            formData.append('title', this.title);
            formData.append('description', this.description);
            formData.append('tags', JSON.stringify(this.selectedTags));
            formData.append('channel_name', this.channelName);
            formData.append('channel_id', this.data.youtube_video_data.items[0].snippet.channelId);
            formData.append('video_id', this.data.youtube_video_data.items[0].id);
            formData.append('published_at', this.data.youtube_video_data.items[0].snippet.publishedAt);
            try {
                axios.post(`/api/save_youtube_post`, formData).then((res) => {
                    if(res){
                        this.$toast.add({severity:'success', summary: 'Success', detail: 'Post Created Successfully', life: 3000});
                        this.$emit('postCreated');
                        this.clear();
                        this.visible = false
                    }
                }).catch((err) => {
                    this.$toast.add({ severity: 'error', summary: 'Error', detail: err.response.data.errors.youtube_video_url?.[0], life: 3000 });
                });
            } catch (error) {
                console.log(errors.response)
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
            this.channelName = ''
        },

    }
};
</script>
