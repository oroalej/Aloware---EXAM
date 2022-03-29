<template>
    <div class='relative mb-6'>
        <div class='relative mb-4'>
            <div class='relative mb-2'>
                <div class='flex flex-row justify-between'>
                    <p class='font-medium uppercase mb-1'>{{ comment.name }}</p>
                    <span class='text-sm'>{{ formatDate }}</span>
                </div>
                <p class='text-sm'>
                    {{ comment.message }}
                </p>
            </div>
            <div class='relative' v-if='comment.depth < 3'>
                <button class='text-sm hover:text-gray-900 transition-colors' @click='onToggleReply'>Reply</button>
            </div>
        </div>

        <CommentForm
            v-if='isOpen'
            :parent-id='comment.id'
            :depth='comment.depth'
            @added='addToList'
            @cancel='onToggleReply'
        />

        <div class='ml-8' v-if='comment.children.length'>
            <Comment v-for='child in comment.children'
                     :key='`${comment.id}-${child.id}`'
                     :comment='child'
                     @added="$emit('added', $event)"
            />
        </div>
    </div>
</template>

<script>
import CommentForm from './CommentForm';
import { differenceInCalendarDays, format, formatDistanceToNow } from 'date-fns';

export default {
    name: 'Comment',
    props: {
        comment: Object
    },
    data: () => ({
        isOpen: false
    }),

    computed: {
        formatDate() {
            const date = new Date(this.comment.created_at);
            const difference = differenceInCalendarDays(new Date(), date);

            if (difference > 8) {
                return format(date, 'MMM d, yyyy');
            }

            return formatDistanceToNow(date) + ' ago';
        }
    },

    methods: {
        onToggleReply() {
            this.isOpen = !this.isOpen;
        },
        addToList(payload) {
            this.$emit('added', payload);
            this.onToggleReply();
        }
    },
    components: {
        CommentForm
    }
};
</script>
