<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watchEffect } from 'vue';
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { FeedProps } from '@/types';
import TweetCard from '@/components/tweets/TweetCard.vue';

const props = defineProps<FeedProps>();

const form = useForm({
    content: '',
});

const tweetList = ref({ ...props.tweets });
const isLoading = ref(false);

const postTweet = () => {
    if (form.content.trim().length === 0 || form.content.trim().length > props.maxTweetLength) return;
    form.post(route('tweets.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

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
                <form @submit.prevent="postTweet">
                    <Textarea v-model="form.content" placeholder="What's happening?"
                        class="w-full resize-none rounded-lg border p-3 focus:ring focus:ring-blue-200"
                        :maxlength="props.maxTweetLength"></Textarea>
                    <InputError class="mt-2" :message="form.errors.content" />
                    <div class="mt-3 flex items-center justify-between">
                        <Button>Tweet</Button>
                        <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Tweeted.</p>
                        </Transition>
                    </div>
                </form>
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
