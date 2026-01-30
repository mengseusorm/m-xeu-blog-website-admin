<template>
  <div>
    <h1 class="text-3xl font-bold mb-3">Blog</h1> 
      <Card style="width: 25rem; overflow: hidden" v-for="(post, index) in posts" :key="index">
          <template #header>
              <img alt="user header" :src="post.youtube_image_url" />
          </template>
          <template #title>{{ post.title }}</template>
          <template #subtitle>{{ post.channel_name }}</template>
          <template #content>
             <div class="flex flex-wrap gap-1">
                  <Tag v-for="tag in parseTags(post.tags)" :key="tag" :value="tag" severity="info" />
              </div>
          </template>
          <template #footer>
              <div class="flex gap-4 mt-1">
                  <Button label="Cancel" severity="secondary" variant="outlined" class="w-full" />
                  <Button label="Save" class="w-full" />
              </div>
          </template>
      </Card>

  </div>
</template>

<script>
import axios from 'axios';
import Card from 'primevue/card'
import Tag from 'primevue/tag'; 
export default {
  components: {
    Card,
    Tag
  },
  data() {
    return {
      posts: []
    }
  },
  mounted() { 
      axios
        .get('/api/admin/posts/')
          .then((res) => {
            this.posts = res.data.data;
            console.log(res)
          })
        .catch((error) => {
          console.error('Error fetching posts:', error);
        });
  },
  methods: {
    parseTags(tags) {
      try {
        return JSON.parse(tags);
      } catch (e) {
        return [];
      }
    }
  }
} 
</script>
