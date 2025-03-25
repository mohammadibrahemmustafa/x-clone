<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import Button from '@/components/ui/button/Button.vue';

const props = defineProps<{
  maxTweetLength: number;
}>();

const form = useForm({
  content: '',
});

const handleSubmit = () => {
  const trimmed = form.content.trim();
  if (trimmed.length === 0 || trimmed.length > props.maxTweetLength) return;

  form.post(route('tweets.store'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  });
};
</script>

<template>
  <form @submit.prevent="handleSubmit">
    <Textarea
      v-model="form.content"
      placeholder="What's happening?"
      class="w-full resize-none rounded-lg border p-3 focus:ring focus:ring-blue-200"
      :maxlength="props.maxTweetLength"
    />
    <InputError class="mt-2" :message="form.errors.content" />
    <div class="mt-3 flex items-center justify-between">
      <Button :disabled="form.processing">
        {{ form.processing ? 'Posting...' : 'Tweet' }}
      </Button>
      <Transition
        enter-active-class="transition ease-in-out"
        enter-from-class="opacity-0"
        leave-active-class="transition ease-in-out"
        leave-to-class="opacity-0"
      >
        <p
          v-show="form.recentlySuccessful"
          class="text-sm text-neutral-600"
          aria-live="polite"
        >
          Tweeted.
        </p>
      </Transition>
    </div>
  </form>
</template>
