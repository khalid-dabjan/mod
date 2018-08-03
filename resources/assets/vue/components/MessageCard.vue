<template>
        <div class="item clearfix">
            <div class="theUser clearfix">
                <div class="avatar"><img :src="img" alt=""></div>
                <div class="data">
                    <div class="theName">{{sender}}</div>
                    <div class="time">{{time}}</div>
                </div>
            </div>
            <div class="theMessage">
                {{message}}
            </div>
        </div>
</template>

<script>
export default {
    props: ['message','time','sender','img']
}
</script>
