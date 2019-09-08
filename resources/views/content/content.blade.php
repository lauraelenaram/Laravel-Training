    @extends('principal')
    @section('content')  

        @if(Auth::check())
            @if(Auth::user()->rol_id==1)
                <template v-if="menu==0">
                    <dashboard-component></dashboard-component>
                </template>  

                <template v-if="menu==1">
                    <category-component></category-component>
                </template>

                <template v-if="menu==2">
                    <article-component></article-component>
                </template>

                <template v-if="menu==3">
                    <income-component></income-component>
                </template>

                <template v-if="menu==4">
                    <supplier-component></supplier-component>
                </template>

                <template v-if="menu==5">
                    <sale-component></sale-component>
                </template>

                <template v-if="menu==6">
                    <client-component></client-component>
                </template>

                <template v-if="menu==7">
                    <user-component></user-component>
                </template>

                <template v-if="menu==8">
                    <rol-component></rol-component>
                </template>

                <template v-if="menu==9">
                    <consultincome-component></consultincome-component>
                </template>

                <template v-if="menu==10">
                    <h1>Reporte de ventas</h1>
                </template>

                <template v-if="menu==11">
                    <h1>Ayuda</h1>
                </template>

                <template v-if="menu==12">
                    <h1>Acerca de...</h1>
                </template>

            @elseif(Auth::user()->rol_id==2)
            <template v-if="menu==0">
                <dashboard-component></dashboard-component>
            </template>

            <template v-if="menu==5">
                <sale-component></sale-component>
            </template>

            <template v-if="menu==6">
                <client-component></client-component>
            </template>

            <template v-if="menu==10">
                <h1>Reporte de ventas</h1>
            </template>

            <template v-if="menu==11">
                <h1>Ayuda</h1>
            </template>

            <template v-if="menu==12">
                <h1>Acerca de...</h1>
            </template>

            @elseif(Auth::user()->rol_id==3)
            <template v-if="menu==0">
                <dashboard-component></dashboard-component>
            </template>

            <template v-if="menu==1">
                <category-component></category-component>
            </template>

            <template v-if="menu==2">
                <article-component></article-component>
            </template>

            <template v-if="menu==3">
                <income-component></income-component>   
            </template>

            <template v-if="menu==4">
                <supplier-component></supplier-component>
            </template>

            <template v-if="menu==9">
                <consultincome-component></consultincome-component>
            </template>

            <template v-if="menu==11">
                <h1>Ayuda</h1>
            </template>

            <template v-if="menu==12">
                <h1>Acerca de...</h1>
            </template>

            @else

            @endif

        @endif
        
    @endsection