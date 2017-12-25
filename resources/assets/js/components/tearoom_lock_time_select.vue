<template>
    <el-row style="margin-bottom: 15px;
        border: 1px solid #f2dede;
        border-radius: 3px;
        padding: 10px;
        margin: 5px 0;">
        <el-col :span="4">
            {{datelist.date}}
        </el-col>

        <el-col :span="4">
            <el-button @click="del()" class="el-icon-delete" size="mini" type="danger" round></el-button>

            <el-button @click='isShowBox=!isShowBox' type="primary" size="mini"
                       v-bind:class="{'el-icon-arrow-down':isShowBox,'el-icon-arrow-up':!isShowBox}"
                       round></el-button>
        </el-col>


        <el-col :span="24" v-bind:class="{'show':isShowBox,'hide':!isShowBox}">
            <el-row>
                <el-checkbox :indeterminate="isIndeterminate"
                             style="margin-top:10px;padding:5px;background:#dedede;display:inline-block"
                             v-model="checkAll"
                             @change="handleCheckAllChange(checkAll)">全选
                </el-checkbox>
                <el-checkbox-group class="row" v-model="datelist.selectDate" @change="handleCheckedCitiesChange">

                    <el-checkbox class="col-md-2" v-for="(time,key) in schedule" :label="key" :key="time">{{time}}
                    </el-checkbox>

                </el-checkbox-group>


            </el-row>

        </el-col>
    </el-row>

</template>
<style>

    .show {
        display: block;
    }

    .hide {
        display: none;
    }
</style>
<script>
    let alldate = [];
    for (let i = 0; i < 48; i++) {
        alldate.push(i)
    }

    export default{

        props: [
            'datelist', 'schedule', 'number'
        ],
        data(){
            return {
                isShowBox: false, //是否显示时间点盒子
                checkAll: true,  //是否全选
                isIndeterminate: true,

            }
        },
        methods: {
            //删除一个锁定时间
            del(){
                this.$emit('del', this.number)
            },

            handleCheckAllChange(val) {
                this.datelist.selectDate = val ? alldate : [];
                this.isIndeterminate = false;

            },
            handleCheckedCitiesChange(value) {
                let checkedCount = value.length;
                this.checkAll = checkedCount === alldate.length;
                this.isIndeterminate = checkedCount > 0 && checkedCount < alldate.length;
            }
        }

    }
</script>