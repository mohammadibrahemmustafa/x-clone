<!-- src/components/TweetCard.vue -->
<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { formatDistanceToNow } from 'date-fns';
import Avatar from '@/components/ui/avatar/Avatar.vue';
import AvatarFallback from '@/components/ui/avatar/AvatarFallback.vue';
import AvatarImage from '@/components/ui/avatar/AvatarImage.vue';
import Button from '@/components/ui/button/Button.vue';
import { getInitials } from '@/composables/useInitials';
import { Tweet } from '@/types';

const props = defineProps<{
    tweet: Tweet;
}>();

const formatDate = (date: string): string => {
  return formatDistanceToNow(new Date(date), { addSuffix: true });
};

const deleteTweet = () => {
  router.delete(route('tweets.delete', props.tweet.id), { preserveScroll: true });
};
</script>

<template>
  <div class="border-b p-4">
    <div class="flex items-start space-x-3">
      <Avatar class="size-8 overflow-hidden rounded-full">
        <AvatarImage
          v-if="tweet.user.avatar_url"
          :src="tweet.user.avatar_url"
          :alt="tweet.user.name"
        />
        <AvatarFallback
          class="rounded-lg bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white"
        >
          {{ getInitials(tweet.user?.name) }}
        </AvatarFallback>
      </Avatar>
      <div class="w-full">
        <div class="flex justify-between">
          <span class="font-semibold">{{ tweet.user.username }}</span>
          <span class="text-sm text-gray-500">{{ formatDate(tweet.created_at) }}</span>
        </div>
        <p class="mt-2 text-gray-700">{{ tweet.content }}</p>
        <div
          v-if="tweet.user.id === $page.props.auth.user.id"
          class="mt-3 flex justify-end space-x-4 text-sm text-gray-500"
        >
          <Button
            class="rounded-md border border-red-500 bg-red-500 px-3 py-1 text-sm font-medium text-white transition-colors hover:bg-white hover:text-red-500"
            @click="deleteTweet"
          >
            Delete
          </Button>
        </div>
      </div>
    </div>
  </div>
</template>
