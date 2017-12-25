<template>
    <div class="section">
        <div class="section-title">房间价格选择</div>
        <div class="section-body">
            <div class="form-group">
                <div class="col-md-3">
                    <label class="control-label">价格段</label>
                    <p class="control-label-help">(不要超过6张)</p>
                </div>
                <div class="col-md-9">
                    <div class="block">
                        <div class="col-md-3">
                            <input type="number" v-model="specialPriceDefualt" class="form-control">
                        </div>

                        <el-date-picker
                                v-model="timeQuantum"
                                type="daterange"
                                align="right"
                                :clearable="false"
                                placeholder="选择日期范围"
                                :picker-options="pickerOptions2">
                        </el-date-picker>
                        <div class="col-md-3">
                            <input type="text" v-model="specialPriceDefualtType" class="form-control">
                        </div>

                        <button class="btn btn-primary" v-on:click.prevent="makeprice"> 确定</button>
                        <button v-if="num==0" class="btn btn-xs btn-primary" @click.prevent="add"><i
                                class="fa fa-plus-circle"></i></button>

                        <button v-else class="btn btn-xs btn-danger" @click.prevent="del"><i
                                class="fa fa-minus-circle"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <div class="section-body" style="overflow: hidden">
            <div v-for="(item,key) in specialPriceList" class="col-md-6">
                <div class="col-md-4">
                    <input type="number" v-model="item.price" class="form-control">
                    <input type="type" v-model="item.type" class="form-control">
                </div>
                <el-date-picker
                        v-model="item.date"
                        type="date"
                        default-value="item.date"
                        placeholder="选择日期"
                        :clearable="false"
                        :picker-options="pickerOptions0">
                </el-date-picker>
                <button class="btn btn-xs btn-danger" @click="delPrice(key)"><i class="fa fa-minus-circle"></i></button>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        props: ['num'],
        data(){
            return {
                pickerOptions2: {
                    shortcuts: [{
                        text: '最近一周',
                        onClick(picker) {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                            picker.$emit('pick', [start, end]);
                        }
                    }, {
                        text: '近一个月',
                        onClick(picker) {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                            picker.$emit('pick', [start, end]);
                        }
                    }, {
                        text: '最近三个月',
                        onClick(picker) {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
                            picker.$emit('pick', [start, end]);
                        }
                    }]
                },
                timeQuantum: [],
                specialPriceDefualt: 9000,
                specialPriceDefualtType: '节假日',
                specialPriceList: [],
                pickerOptions0: {
                    disabledDate(time) {
                        return time.getTime() < Date.now() - 8.64e7;
                    }
                },

            }
        },
        methods: {
            makeprice(){
                let data = {
                    timeQuantum: this.timeQuantum,
                    specialPrice: this.specialPriceDefualt,
                    type: this.specialPriceDefualtType
                };
                if (!(this.specialPriceDefualtType && this.specialPriceDefualt && this.timeQuantum)) {

                    this.$message.error('请输入完整信息');
                    return;
                }
                ;
                axios.post('/experienceroom/makePrice', data).then(res => {
                    let response = res.data;
                    if (response.status) {
                        this.specialPriceList = response.data;
                        this.$emit('makeprice', [this.specialPriceList, this.num]);
                    }
                });
                return false;

            },
            add(){
                this.$emit('add')
            },
            del(){
                this.$emit('del', this.num);
            },
            delPrice(key){
                this.specialPriceList.splice(key, 1);
                this.$emit('makeprice', [this.specialPriceList, this.num]);
            }

        }
    }
</script>