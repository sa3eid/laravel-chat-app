<template>
    <ul class="chat">
        <li class="left clearfix" v-for="message in messages" :key="message">
            <div class="chat-body clearfix">
                <div class="header" v-if="message">
                    <strong class="primary-font">
                        {{ message.user.name }}
                    </strong>
                </div>
                <p v-if="message">
                    {{ message.message }}
                </p>
            </div>
        </li>
    </ul>
</template>

<script>

  export default {
    data(){
        return {
            messages: []
        }
    },
    mounted() {
        console.log('Component mounted.')
        axios.get('/messages').then(response => {
            this.messages = response.data;
            // console.log(response.data)
        });

        window.Echo.private('chat')
        .listen('.MessageSent', (e) => {
            this.messages.push({
                message: e.message.message,
                user: e.user
            });

            console.log('messages updated..')
        });
    }
  };
</script>