<script setup lang="ts">
import { ref, defineProps, defineEmits } from 'vue';
import { cn } from '@/lib/utils';
import type { HTMLAttributes } from 'vue';
import { Avatar, AvatarImage } from '@/components/ui/avatar';

const props = defineProps<{
    modelValue?: string | File | null;
    class?: HTMLAttributes['class'];
    accept?: string;
    maxSize?: number; // Maximum file size in MB
}>();

const emits = defineEmits<{
    (e: 'update:modelValue', payload: File | null): void;
}>();

const errorMessage = ref<string | null>(null);

const handleFileChange = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (!input.files || input.files.length === 0) return;

    const file = input.files[0];

    // Validate File Type
    if (props.accept && !file.type.match(props.accept)) {
        errorMessage.value = 'Ungültiger Dateityp. Bitte wählen Sie eine gültige Datei.';
        emits('update:modelValue', null);
        return;
    }

    // Validate File Size
    if (props.maxSize && file.size > props.maxSize * 1024 * 1024) {
        errorMessage.value = `Die Datei darf maximal ${props.maxSize}MB groß sein.`;
        emits('update:modelValue', null);
        return;
    }

    errorMessage.value = null;
    emits('update:modelValue', file);
};
</script>

<template>
    <Avatar v-if="modelValue && typeof modelValue === 'string'" class="w-32 h-32 overflow-hidden rounded-lg">
        <AvatarImage :src="modelValue" />
    </Avatar>
    <input
        type="file"
        :class="cn(
            'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
            props.class
        )"
        @change="handleFileChange"
        :accept="props.accept"
    />
    <p v-if="errorMessage" class="text-sm text-red-600 mt-1">{{ errorMessage }}</p>
</template>
