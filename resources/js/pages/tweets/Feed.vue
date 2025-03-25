<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, watchEffect } from 'vue';
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { FeedProps } from '@/types';
import TweetCard from '@/components/tweets/TweetCard.vue';
import TweetForm from '@/components/tweets/TweetForm.vue';

const props = defineProps<FeedProps>();

const tweetList = ref({ ...props.tweets });
const isLoading = ref(false);

const loadMore = async () => {
    if (!tweetList.value.next_page_url) return;
    isLoading.value = true;

    const response = await fetch(tweetList.value.next_page_url, {
        headers: { Accept: 'application/json' },
    });

    const data = await response.json();
    tweetList.value.data.push(...data.data);
    tweetList.value.next_page_url = data.next_page_url;
    isLoading.value = false;
};

watchEffect(() => {
    tweetList.value = { ...props.tweets };
});
</script>

<template>

    <Head title="Feed" />

    <AppLayout>
        <div class="mx-auto w-full max-w-2xl rounded-lg bg-white p-6 shadow-md">
            <!-- Tweet Input -->
            <div class="mb-6 border-b p-4">
                <TweetForm :max-tweet-length="props.maxTweetLength"/>
            </div>

            <!-- Tweet Feed -->
            <div v-for="tweet in tweetList.data" :key="tweet.id">
                <TweetCard :tweet="tweet"></TweetCard>
            </div>

            <!-- Load More Button -->
            <Button v-if="tweetList.next_page_url" @click="loadMore" :disabled="isLoading"
                class="mt-6 w-full rounded-lg bg-gray-200 p-2 text-gray-800 hover:bg-gray-300">
                {{ isLoading ? 'Loading...' : 'Load More' }}
            </Button>
        </div>
    </AppLayout>
</template>
