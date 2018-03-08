<template>

    <div class="quill-editor-example">
        <el-upload style="display:none"
                :id="'quill-upload'+id"
                v-loading="loading"
                class="avatar-uploader"
                element-loading-text="拼命上传中。。。"
                list-type="picture-card"
                action="/upload/qiniu"
                :show-file-list="false"
                :headers="{'X-CSRF-TOKEN':token}"
                :on-success="handleAvatarSuccess"
                :before-upload="beforeAvatarUpload"
        >
        </el-upload>
        <!-- quill-editor -->
        <el-row v-loading="loading">
        <quill-editor ref="myTextEditor"
                      v-model="content"
                      :id="id"
                      :options="editorOption"
                      @blur="onEditorBlur($event)"
                      @focus="onEditorFocus($event)"
                      @ready="onEditorReady($event)">
        </quill-editor>
        </el-row>
        <!--<div class="quill-code">-->
        <!--<div class="title">Code</div>-->
        <!--<code class="hljs xml" v-html="contentCode"></code>-->
        <!--</div>-->
    </div>

</template>

<script>
    import hljs from 'highlight.js'
    import 'quill/dist/quill.core.css'
    import 'quill/dist/quill.snow.css'
    import 'quill/dist/quill.bubble.css'
    import {quillEditor} from 'vue-quill-editor'
    import {ImageDrop} from 'quill-image-drop-module'
    import ImageResize from 'quill-image-resize-module'
    Quill.register('modules/imageDrop', ImageDrop)
    Quill.register('modules/imageResize', ImageResize)
    const Font = Quill.import('formats/font');
    Font.whitelist = ['Arial', '微软雅黑', 'serif', 'sans-serif', '宋体', '黑体', 'monospace'];
    Quill.register(Font, true);

    //设置

   export default{
        components: {
            quillEditor,
        },
        props: ["editdata","id"],
        data() {
            return {
                name: 'quill-editor',
                content: this.editdata?`${this.editdata}`:`开始您的内容`,
                imgUrl: "",
                loading: false,
                token:window.axios.defaults.headers.common['X-CSRF-TOKEN'],
                editorOption: {
                    upload_id:this.id,
                    modules: {
                        toolbar: {
                            container: [
                                ['bold', 'italic', 'underline', 'strike'],
                                ['blockquote', 'code-block'],
                                [{'header': 1}, {'header': 2}],
                                [{'list': 'ordered'}, {'list': 'bullet'}],
                                [{'script': 'sub'}, {'script': 'super'}],
                                [{'indent': '-1'}, {'indent': '+1'}],
                                [{'direction': 'rtl'}],
                                [{'size': ['small', false, 'large', 'huge']}],
                                [{'header': [1, 2, 3, 4, 5, 6, false]}],
                                [{'font': ['Arial', '微软雅黑', 'serif', 'sans-serif', '宋体', '黑体', 'monospace']}],
                                [{'color': []}, {'background': []}],
                                [{'align': []}],
                                ['clean'],
                                ['link', 'image', 'video']
                            ],
                            handlers: {
                                image(value) {
                                    if (value) {
                                        let id="#quill-upload"+this.quill.options.upload_id+" input"
                                        document.querySelector(id).click()
                                    } else {
                                        this.quill.format('image', false);
                                    }
                                }
                            },
                        },
                        syntax: {
                            highlight: text => hljs.highlightAuto(text).value
                        },
                        history: {
                            delay: 1000,
                            maxStack: 50,
                            userOnly: false
                        },
                        imageDrop: true,
                        imageResize: {
                            displayStyles: {
                                backgroundColor: 'black',
                                border: 'none',
                                color: 'white'
                            },
                            modules: ['Resize', 'DisplaySize', 'Toolbar']
                        }
                    }
                }
            }
        },
        methods: {
            onEditorBlur(editor) {
                // console.log('editor blur!', editor)
            },
            onEditorFocus(editor) {
                //  console.log('editor focus!', editor)
            },
            onEditorReady(editor) {
                // console.log('editor ready!', editor)
            },
            handleAvatarSuccess(res) {
                this.loading=false;
                // res为图片服务器返回的数据
                // 获取富文本组件实例
                console.log(res)
                let quill = this.$refs.myTextEditor.quill
                // 如果上传成功
                if (res.url) {
                    // 获取光标所在位置
                    let length = quill.getSelection().index;
                    // 插入图片  res.info为服务器返回的图片地址
                    quill.insertEmbed(length, 'image', res.url+"?imageView2/0/h/750");
                    // 调整光标到最后
                    quill.setSelection(length + 1)
                } else {
                    this.$message.error('图片插入失败')
                }
            },

            beforeAvatarUpload(file) {
                const isJPG = (file.type === 'image/jpeg' || file.type === 'image/png' || file.type == 'image/gif');
                const isLt2M = file.size / 1024 / 1024 < 2;


                if (!isJPG) {
                    this.$message.error('上传头像图片只能是 JPG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                    this.loading=false
                    return;
                }
                this.loading = true;
                return isJPG && isLt2M;
            },
        },
        computed: {
            editor() {
                return this.$refs.myTextEditor.quill
            },

        },
        watch: {
            content(val) {
                //  console.log(val)
                // console.log(hljs.highlightAuto(val).value)
                this.$emit('update:editdata', val)
            }
        }

    }

</script>

<style lang="scss">
    .el-form-item__content {
        line-height: 24px;
        position: relative;
        font-size: 14px;
    }

    .ql-editor {
        min-height: 500px;
        height:auto;

    }

    .quill-code {
        border: none;
        height: auto;
        > code {
            width: 100%;
            margin: 0;
            padding: 1rem;
            border: 1px solid #ccc;
            border-top: none;
            border-radius: 0;
            height: 10rem;
            overflow-y: auto;
            resize: vertical;
        }
    }

    .quill-editor {
        .ql-container{
            height:800px;
            overflow-y: auto;
        }
        /*工具栏内用*/
        .ql-font {
            span[data-value="Arial"]::before {
                content: "Arial" !important;
                font-family:"Arial";
            }
            span[data-value="宋体"]::before {
                content: "宋体" !important;
                font-family:"宋体";
            }
            span[data-value="黑体"]::before {
                content: "黑体" !important;
                font-family: "黑体";
            }
            span[data-value="微软雅黑"]::before {
                content: "微软雅黑" !important;
                font-family: "微软雅黑";
            }
        }
        /*编辑器内容用*/
        .ql-font-Arial {
            font-family:"Arial" !important;
        }
        .ql-font-宋体 {
        font-family:"宋体" !important;
        }
        .ql-font-黑体 {
            font-family:"黑体" !important;
        }
        .ql-font-微软雅黑 {
            font-family:"微软雅黑" !important;
        }
    }

</style>