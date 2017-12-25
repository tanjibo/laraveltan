<template>
    <div>

        <el-form-item class="col-md-6" v-for="item in lockdate" :key="item.key">
            {{item}}
            <!--<el-date-picker-->
                    <!--v-model="item"-->
                    <!--type="date"-->
                    <!--style="width:150px;"-->
                    <!--placeholder="选择日期">-->
            <!--</el-date-picker>-->
            <el-button type="danger" icon="el-icon-circle-close" size="mini" round @click="delDate(item)"></el-button>
        </el-form-item>
    </div>

</template>
<script>

    export default {
        props: ['lockdate'],
        data(){
            return {

                token:window.axios.defaults.headers.common['X-CSRF-TOKEN']
            }
        },

        methods: {
           delDate(item){
               console.log(this.lockdate);
              this.lockdate.splice(item,1)
           }
        }

    }


</script>