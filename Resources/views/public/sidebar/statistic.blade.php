@if($configuration['widget__statistics'])
    <div class="flex-sidebar-element stats">
        <div>{!! trans_choice('forum_arckene::forum.Widget.Statistic.0', forum__getThreads()->where('thread_id', null)->count()) !!}</div>
        <div>{!! trans_choice('forum_arckene::forum.Widget.Statistic.1', forum__getThreads()->count()) !!}</div>
        <div>{!! trans_choice('forum_arckene::forum.Widget.Statistic.2', forum__getUsers()->count()) !!}</div>
        <div>{!! __('forum_arckene::forum.Widget.Statistic.3', ['member' => user(forum__getUsers()->getLastRow()['id'])->pseudo ]) !!}</div>
    </div>
@endif