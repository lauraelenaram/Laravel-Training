<template>
    <li class="nav-item d-md-down-none">
        <a class="nav-link" href="#" data-toggle="dropdown">
            <i class="icon-bell"></i>
            <span class="badge badge-pill badge-danger">{{notifications.length}}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header text-center">
                <strong>Notificaciones</strong>
            </div>
            <div v-if="notifications.length">
                <li v-for="item in list" :key="item.id">
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-envelope-o"></i> {{item.incomes.msg}}
                        <span class="badge badge-success">{{item.incomes.number}}</span>
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-tasks"></i> {{item.sales.msg}}
                        <span class="badge badge-danger">{{item.sales.number}}</span>
                    </a>
                </li>
            </div>
            <div v-else> 
                <a><span>No tiene notificaciones</span></a>
            </div>
        </div>
    </li>
</template>
<script>
export default {
    props: ['notifications'],
    data()
    {
        return {
            notificationsArray: []
        }
    },
    computed:
    {
        list: function()
        {
            this.notificationsArray= Object.values(this.notifications[0]);
            if(this.notifications == '')
            {
                return this.notificationsArray= [];
            }
            else
            {
                this.notificationsArray= Object.values(this.notifications[0]);
                if(this.notificationsArray.length > 3)
                {
                    return Object.values(this.notificationsArray[4]);
                }
                else
                {
                    return Object.values(this.notificationsArray[0]);
                }
            }
        }
    }
}
</script>