<template>
    <div>
        <el-form-item v-for="item in Prices" :key="item.key">
            <el-input style="width:200px" v-model="item.durations" placeholder="时长">
            </el-input>
            <el-input style="width:200px;" v-model="item.fee" placeholder="费用">
            </el-input>
            <el-radio-group v-model="item.status">
                <el-radio label='1'>启用</el-radio>
                <el-radio label='0'>关闭</el-radio>
            </el-radio-group>
        </el-form-item>
    </div>


</template>

<script>
    export default{
        props:["prices"],
        data(){
            return {
             Prices:this.prices
            }
        }
    }
</script>