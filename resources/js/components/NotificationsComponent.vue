<template>
    <li class="dropdown notifications-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-bell-o"></i>
            <span class="label label-warning" v-if="notifications != null">{{ notifications.lingth }}</span>
        </a>
        <ul class="dropdown-menu">
            <li class="header" v-if="notifications == null">لا توجد إشعارات جديدة</li>
            <li v-if="notifications != null">
                <ul class="menu">
                    <li class="header">لديك {{ notifications.lingth }} تنبيهات جديدة</li>
                    <li v-for="notifi in notifications">
                        <a href="#"> 
                            <p>{{ notifi.data }}</p>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
</template>


<script>
import axios from "axios";
export default {
    data: function () {
        return {
            loading: true,
            notifications: null,
            read: null
        }
    },
    mounted() {
        axios.get('/notifications')
      .then(response => {
        this.notifications = response.data
        console.log(response.data);
      })
      .finally(() => {
        this.loading = false
      });
    },
    methods: {
      readNotification() {
        // 
      },
    }
}
</script>
