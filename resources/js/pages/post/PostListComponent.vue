<template>
    <div class="card">
        <DataTable :value="posts" v-model:filters="filters" paginator :rows="10" dataKey="id" filterDisplay="row"
            :loading="loading" :globalFilterFields="['title', 'channel_name', 'video_id']">
            <template #header>
                <div class="flex justify-between items-center gap-3">
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="filters.global.value" class="w-full" placeholder="Search video, channel, title" />
                    </IconField>
                    <CreatePostModal :props="'Create'" @postCreated="fetchPosts" />
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
                    <iframe width="160" height="80" :src="`https://www.youtube.com/embed/${data.video_id}`"
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
                    <Button icon="pi pi-pencil" class="mr-2" @click="editProduct(data.data)" />
                    <Button icon="pi pi-trash" severity="danger" @click="confirmDeleteProduct(data.data)" />
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
                .get('/api/posts')
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
            axios.delete(`/api/posts/${id}`)
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
    }
}
</script>

<style scoped>
iframe {
    border-radius: 8px;
}
</style>
