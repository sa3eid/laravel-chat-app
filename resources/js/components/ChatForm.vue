<template>
    <div class="input-group">
        <input id="btn-input" type="text" name="message" class="form-control input-sm chat-box" placeholder="Type your message here..." v-model="newMessage" @keyup.enter="sendMessage"><br><br>
        <input type="file" class="form-control input-sm" id="files" name="files" @change="onFilesUpload" multiple><br>
        <span class="input-group-btn">
            <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
                Send
            </button>
        </span>
    </div>
</template>

<script>
    export default {
        props: ['user'],

        data() {
            return {
                newMessage: '',
                files: []
            }
        },

        methods: {
            sendMessage() {

                if(this.newMessage == ''){
                    alert('insert a message before submit!')
                }

                let requestBody = new FormData();
                requestBody.append('message', this.newMessage)

                if(this.files.length != 0 ){
                    for( var i = 0; i < this.files.length; i++ ){
                        let file = this.files[i];
                        // console.log(file);
                        requestBody.append('files[' + i + ']', file);
                    }
                }

                axios.post('/message/send', requestBody).then(response => {
                    console.log(response.data);
                })

                this.newMessage = ''
                this.files = []
            },
            onFilesUpload(event) {
                this.files = event.target.files
            }
        }
    }
</script>

<style scoped>
    .chat-box{
        border-radius: 25px;
        padding: 20px;
        width: 50px;
        height: 37px;
    }
    #files{
        padding: .5em;
        margin: .1em;
    }

    .input-group > .form-control, .input-group > .form-select, .input-group > .form-floating {
        position: relative;
        flex: 1 1 auto;
        width: 100%;
        min-width: 0;
    }
</style>
