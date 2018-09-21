<template>

    <div class="card">

        <div class="card-header">
            @lang('Latest changes')
            @can ('clear-history')
            <button class="btn-clear-history" id="clear-history" @click="clearHistory">@lang('Clear')</button>
            @endcan
        </div>

        <div class="table-responsive">

            <table v-for="model in displayedModels" class="table table-main mb-0">
                <thead>
                    <tr>
                        <th class="created_at">Date</th>
                        <th class="title">Title</th>
                        <th class="historable_table">Module</th>
                        <th class="action">Action</th>
                        <th class="user_name">User</th>
                    </tr>
                </thead>

                <tbody>
                    <tr ng-repeat="model in displayedModels">
                        <td>@{{ model.created_at | dateFromMySQL:'dd/MM/yyyy HH:mm' }}</td>
                        <td>
                            <a @if="model.href" :href="model.href+'?locale='+model.locale">@{{ model.title }}</a>
                            <span @if="!model.href">@{{ model.title }}</span>
                            <span @if="model.locale">(@{{ model.locale }})</span>
                        </td>
                        <td>@{{ model.historable_table }}</td>
                        <td>
                            <span class="fa fa-fw" :class="model.icon_class"></span> @{{ model.action }}
                        </td>
                        <td class="user_name"><div class="text-truncatable">@{{ model.user_name }}</div></td>
                    </tr>
                </tbody>
            </table>

        </div>

    </div>

</template>
