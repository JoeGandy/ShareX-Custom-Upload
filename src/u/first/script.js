


const app = new Vue({
    el: "#app",
    data() {
        return {
            step: 1,
            config: {
                page_title: null,
                secure_key: null,
                output_url: null,
                request_url: null,
                redirect_url: null,
                enable_random_name: false,
                random_name_length: "8",
                enable_delete: false,
                enable_tooltip: false,
                enable_lightbox: false,
                enable_zip_dump: false,
                username: null,
                password: null
            },
            placeholder: {
                page_title: "Uploading page",
                secure_key: "somerandomlongstringoftextforkey",
                output_url: "http://awesomedomain.com/u/",
                request_url: "http://awesomedomain.com/upload.php",
                redirect_url: "http://awesomedomain.com/"
            }
        };
    },
    methods: {
        prev() {
            this.step--;
        },
        next() {
            this.step++;
        },
        submit() {

        },
        makekey() {
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < 33; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return this.config.secure_key = result;
        }
    }
});