<script>
    import Favorite from './Favorite.vue';
    export default {
        name: 'reply',
        props: ['attributes'],
        components: { Favorite },
        data() {
            return {
                editing: false,
                body: this.attributes.body,
                oldBody: this.attributes.body
            }
        },
        methods: {
            update() {
                axios.patch('/replies/' + this.attributes.id, {
                    body: this.body
                })
                // .then(() => {
                    this.editing = false

                    flash('Updated!')
                // })

            },
            cancelEdit() {
                this.body = this.oldBody
                this.editing = false
                flash('Cancelled')
            },
            destroy() {
                axios.delete('/replies/' + this.attributes.id)

                $(this.$el).fadeOut(300, () => {
                    flash('Your reply has been deleted')
                });
            }
        }
    }
</script>