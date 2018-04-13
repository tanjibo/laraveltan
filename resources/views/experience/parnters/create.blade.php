@extends("layouts.main")

@section("content")
    <div class="content" id="app">

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">添加合作</h3>
            </div>
            <!-- /.box-header -->
            <el-form  label-width="80px">
                <div class="box-body">
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                        <div class="panel box box-primary">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse">
                                        房间信息
                                    </a>
                                </h4>
                            </div>
                            <div class="panel-collapse collapse in">
                                <div class="box-body">
                                    <el-form-item label="合作方名称">
                                        <el-input v-model="name"></el-input>
                                    </el-form-item>

                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <el-form-item>

                                <el-button type="primary" @click="submitForm()">添加</el-button>

                            </el-form-item>
                        </div>
                    </div>
                </div>
            </el-form>
            <!-- /.box-body -->
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        new Vue({
            el: "#app",
            data: {
                name: ''
            },
            methods: {
                submitForm(){
                    if (!this.name) {
                        this.$message.warning("合作方名称不能为空");
                        return false;
                    }
                    this.$http.post("{{route('experience_partners.store')}}", {name: this.name}).then(res => {
                        window.location.href="{{route('experience_partners.index')}}"
                    })
                }
            }
        })
    </script>

@endsection