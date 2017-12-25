<template>
    <el-form-item>
        <div  :id="id"></div>
    </el-form-item>
</template>

<script>

    export default{
        props:['editdata','id'],
        data(){
            return {
                editContent:this.editdata
            }
        },
        mounted(){
            let E = window.wangEditor;
            let editor = new E('#'+this.id);
            editor.customConfig.zIndex = 100;
            editor.customConfig.onchange = (html) => {

                this.editContent = html

            };
            editor.create();
            editor.txt.html(this.editContent);
        },
        watch: {

            editContent(val) {
                this.$emit('update:editdata', val)
            },
        },
        methods:{

        }
    }
</script>