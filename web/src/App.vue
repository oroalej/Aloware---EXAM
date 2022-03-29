<template>
    <div class='sm:w-2/3 md:w-1/2 lg:w-1/3 mx-auto text-gray-700 font-sans px-4 text-left'>
        <div class='divide-y'>

            <div class='relative py-4'>
                <h1 class='text-2xl font-bold uppercase mb-4'>lorem</h1>

                <div class='relative mb-8'>
                    <p class='mb-4' v-for='n in 2' :key='n'>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus consequuntur, cumque
                        dignissimos dolores
                        dolorum esse excepturi harum iusto magni nemo nisi perspiciatis quaerat rerum sapiente ullam
                        veniam
                        veritatis
                        voluptatibus.
                    </p>
                </div>
            </div>

            <div class='relative py-4'>
                <CommentForm
                    @added='addToList'
                />
            </div>

            <div class='relative py-4'>
                <div class='mb-8' v-for='comment in comments' :key='comment.id'>
                    <Comment
                        :comment='comment'
                        @added='addToList'
                    />
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import Comment from './components/Comment';
import CommentForm from './components/CommentForm';
import { getApi } from '@/utils/fetch';

export default {
    name: 'App',
    data: () => ({
        comments: []
    }),
    mounted() {
        getApi('api/comments', 'GET')
            .then(response => response.json())
            .then(data => {
                this.comments = data;
            });
    },

    methods: {
        addToList(data) {
            if (data.parent_id) {
                this.comments = this.appendToPosition(this.comments, data);
            } else {
                this.comments = [data, ...this.comments];
            }
        },

        appendToPosition(list, payload) {
            return list.map(item => {
                if (item.id === payload.parent_id) {
                    item.children = [payload, ...item.children];
                } else if (item.children) {
                    item.children = this.appendToPosition(item.children, payload);
                }

                return item;
            });
        }
    },

    components: {
        Comment,
        CommentForm
    }
};
</script>
