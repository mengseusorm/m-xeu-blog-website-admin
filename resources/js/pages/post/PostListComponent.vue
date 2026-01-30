<template>
    <div class="card">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-2 mb-4">
            <Card> 
                <template #title>Total Videos</template>
                <template #subtitle>{{ countVideo(posts) }}</template>
                <template #content> 
                </template> 
            </Card>
            <Card > 
                <template #title>Total Channel</template>
                <template #subtitle>{{ countChannel(posts) }}</template>
                <template #content> 
                </template> 
            </Card> 
        </div>
        <DataTable :value="posts" v-model:filters="filters" paginator :rows="10" dataKey="id" filterDisplay="row" resizableColumns columnResizeMode="fit" :loading="loading" :globalFilterFields="['title', 'channel_name', 'video_id']">
            <template #header>
                <div class="flex justify-between items-center gap-3">
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="filters.global.value" class="w-full" placeholder="Search video, channel, title" />
                    </IconField>
                    <CreatePostModal ref="createModal" :props="'New Post'" @postCreated="fetchPosts" />
                </div>
            </template>
            <template #empty>
                <div class="flex justify-center">
                    <i class="pi pi-inbox" style="font-size: 2rem"></i>
                </div>
            </template>
            <template #loading> Loading customers data. Please wait. </template>
            <Column field="No" header="NO." style="width: 5rem">
                <template #body="{ data }">
                    {{ posts.indexOf(data) + 1 }}
                </template>
            </Column>
            <Column header="Video">
                <template #body="{ data }">
                    <iframe width="120" height="60" :src="`https://www.youtube.com/embed/${data.video_id}`"
                        frameborder="0" allowfullscreen></iframe>
                </template>
            </Column>
            <Column field="title" header="Title" style="min-width: 18rem">
                <template #body="{ data }">
                    <strong>{{ data.title }}</strong>
                </template>
                <template v-if="posts?.length > 0" #filter="{ filterModel, filterCallback }">
                    <InputText v-model="filterModel.value" @input="filterCallback()" placeholder="Search title" />
                </template>
            </Column>
            <Column field="channel_name" header="Channel" style="min-width: 14rem">
                <template v-if="posts?.length > 0" #filter="{ filterModel, filterCallback }">
                    <InputText v-model="filterModel.value" @input="filterCallback()" placeholder="Search channel" />
                </template>
            </Column>
            <Column header="Tags" style="min-width: 18rem">
                <template #body="{ data }">
                    <div class="flex flex-wrap gap-1">
                        <Tag v-for="tag in parseTags(data.tags)" :key="tag" :value="tag" severity="info" />
                    </div>
                </template>
            </Column>
            <Column header="Published At">
                <template #body="{ data }">
                    {{ formatDate(data.published_at) }}
                </template>
            </Column>
            <Column header="Created">
                <template #body="{ data }">
                    {{ formatDate(data.created_at) }}
                </template>
            </Column>
            <Column  style="min-width: 12rem">
                <template #body="data">
                    <Button label="Edit"  variant="link" class="mr-2" @click="editProduct(data.data)" size="small" />
                    <Button label="Delete"  variant="link"  @click="confirmDeleteProduct(data.data)" size="small" />
                </template>
            </Column>

        </DataTable>
    </div>
</template>
<script>
import axios from 'axios'
import { FilterMatchMode } from '@primevue/core/api'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import Tag from 'primevue/tag'
import IconField from 'primevue/iconfield'
import InputIcon from 'primevue/inputicon'
import CreatePostModal from './CreatePostModal.vue'
import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import Card  from 'primevue/card' 
export default {
    components: {
        DataTable,
        Column,
        InputText,
        Tag,
        IconField,
        InputIcon,
        CreatePostModal,
        Button,
        Dialog,
        Card
    },

    data() {
        return {
            posts: [],
            loading: true,
            filters: {
                global: { value: null, matchMode: FilterMatchMode.CONTAINS },
                title: { value: null, matchMode: FilterMatchMode.CONTAINS },
                channel_name: { value: null, matchMode: FilterMatchMode.CONTAINS }
            },
        }
    },

    mounted() {
        this.fetchPosts()
    },

    methods: {
        fetchPosts() {
            this.loading = true
            axios
                .get('/api/admin/posts/')
                .then((res) => {
                    this.posts = res.data.data ?? []
                    this.loading = false
                })
                .catch((err) => {
                    console.error('Fetch error:', err)
                    this.posts = []
                    this.loading = false
                })
        },
        parseTags(tags) {
            try {
                const parsed = JSON.parse(tags)
                return Array.isArray(parsed) ? parsed : []
            } catch {
                return []
            }
        },

        formatDate(date) {
            return new Date(date).toLocaleDateString()
        },
        confirmDeleteProduct(product) {
            this.$swal({
                title: 'Are you sure?',
                text: `You are about to delete the post titled "${product.title}". This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deletePost(product.id);
                    this.$swal(
                        'Deleted!',
                        'The post has been deleted.',
                        'success'
                    );
                }
            });
        },
        deletePost(id) {
            axios.delete(`/api/admin/posts/${id}`)
                .then((res) => {
                    this.fetchPosts();
                })
                .catch((err) => {
                    console.error('Delete error:', err);
                    this.$swal(
                        'Error!',
                        'There was an error deleting the post.',
                        'error'
                    );
                });
        },
        editProduct(product) { 
            this.$refs.createModal.openEditModal(product);
        },
        countVideo(posts){ 
            const map = {}

            posts.forEach(item => {
                const key = item.youtube_video_url
                if (!key) return

                map[key] = (map[key] || 0) + 1
            }) 
            return Object.values(map).reduce((sum, val) => sum + val, 0) 
        },
        countChannel(posts){ 
            const map = {}

                posts.forEach(item => {
                    if (!item.channel_name) return
                    map[item.channel_name] = true
                })

            return Object.keys(map).length
        }
    }
}
</script>

<style scoped>
iframe {
    border-radius: 8px;
}
</style>
