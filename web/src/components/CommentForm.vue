<template>
    <form action='' v-on:submit.prevent='onSubmit'>
        <div class='relative mb-3'>
            <label for='name' class='block mb-1.5'>Name: <span class='text-red-600'>*</span></label>
            <input
                id='name'
                name='name'
                type='text'
                class='text-sm w-full px-2.5 py-1.5 border border-gray-200 outline-blue-400'
                v-model.trim='name'
            >

            <ul v-if="errors['name']" class='text-red-600 text-sm mt-1'>
                <li v-for="(message, index) in errors['name']" :key='index'>{{ message }}</li>
            </ul>
        </div>

        <div class='relative mb-3'>
            <label for='message' class='block mb-1.5'>Message: <span class='text-red-600'>*</span></label>
            <textarea
                name='message'
                id='message'
                rows='2'
                class='text-sm w-full px-2.5 py-1.5 border border-gray-200 outline-blue-400 min-h-[70px]'
                v-model.trim='message'
            />
            <ul v-if="errors['message']" class='text-red-600 text-sm mt-1'>
                <li v-for="(message, index) in errors['message']" :key='index'>{{ message }}</li>
            </ul>
        </div>

        <div class='mb-3 flex flex-row gap-2 justify-end'>
            <button
                type='button'
                @click='onCancel'
                class='px-6 py-1.5 font-medium text-sm bg-white hover:bg-gray-100 text-gray-700 hover:bg-opacity-90 active:scale-95 transform transition-all disabled:bg-gray-400 disabled:cursor-not-allowed'
            >
                Cancel
            </button>

            <button
                type='submit'
                :disabled='isLoading'
                class='px-6 py-1.5 font-medium text-sm bg-blue-500 hover:bg-blue-600 hover:bg-opacity-90 active:scale-95 transform transition-all text-white disabled:bg-gray-400 disabled:cursor-not-allowed'
            >
                Submit
            </button>
        </div>
    </form>
</template>

<script>
import { postApi } from '@/utils/fetch';

export default {
    name: 'CommentForm',
    props: {
        parentId: Number,
        depth: Number
    },
    data: () => ({
        name: '',
        message: '',
        isLoading: false,
        errors: []
    }),

    methods: {
        resetForm() {
            this.name = '';
            this.message = '';
            this.errors = [];
        },
        onCancel() {
            this.resetForm();
            this.$emit('cancel');
        },
        onSubmit() {
            this.isLoading = true;

            postApi('api/comments/', {
                name: this.name,
                message: this.message,
                parent_id: this.parentId,
                depth: this.depth
            }).then(result => {
                this.resetForm();
                this.$emit('added', result);
            }).catch(({ errors }) => {
                this.errors = errors;
            })
                .finally(() => {
                    this.isLoading = false;
                });
        }
    }
};
</script>
