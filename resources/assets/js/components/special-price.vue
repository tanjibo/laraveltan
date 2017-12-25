<template>
    <div>

        <el-form-item class="col-md-6" v-for="item in specialprice" :key="item.key">
            <el-input class="inline" v-model="item.price" placeholder="价格">
            </el-input>
            <el-input class="inline" v-model="item.type" placeholder="价格" with="40">
            </el-input>
            <el-date-picker
                    v-model="item.date"
                    type="date"
                    style="width:150px;"
                    placeholder="选择日期">
            </el-date-picker>
            <el-button type="danger" icon="el-icon-circle-close" plain @click="delPrice(item)"></el-button>
        </el-form-item>
    </div>

</template>
<script>

    export default{
        props: ['specialprice'],
        data(){

            return {
                specialPrice: this.specialprice
            }
        },
        methods: {
            delPrice(item){
                this.specialprice.splice(item,1)
            }
        }
    }
</script>