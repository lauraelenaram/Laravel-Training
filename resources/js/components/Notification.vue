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
                <li v-for="item in notifications" :key="item.id">
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-envelope-o"></i> {{item.data.data.incomes.msg}}
                        <span class="badge badge-success">{{item.data.data.incomes.number}}</span>
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-tasks"></i> {{item.data.data.sales.msg}}
                        <span class="badge badge-danger">{{item.data.data.sales.number}}</span>
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

        }
    }
}
</script>