

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-edit"></i> {{ __('forum_arckene::admin.Users.title') }}</h6>
    </div>
    <div class="card-body">
        <table id="example" class="table table-striped" style="width:100%">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ __('messages.Form.Pseudo') }}</th>
                <th>{{ __('messages.Form.Role') }}</th>
                <th>State</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>

            @foreach($users as $u)
                <tr>
                    <td style="vertical-align: middle;"><img src="{{ userInfo($u['id'], 'avatar') }}" alt="{{ user($u['id'])->pseudo }}" style="width: 40px"> </td>
                    <td style="vertical-align: middle;">{{ user($u['id'])->pseudo }}</td>
                    <td style="vertical-align: middle;">
                        @php($userRanks = userRanks($u['id']))
                        @foreach($userRanks as $rank)
                            <span class="badge badge-pill" style="background: {{ $rank['background'] }}; color: {{ $rank['color'] }}; font-size: 1rem">{{ rankName($rank['name']) }}</span>
                        @endforeach
                    </td>
                    <td style="vertical-align: middle;">
                        @if(userInfo($u['id'], 'banned'))
                            <span class="badge badge-pill" style="background: #c40d0d; color: white; font-size: 1rem">Banned</span>
                        @else
                            <span class="badge badge-pill" style="background: #19e029; color: white; font-size: 1rem">Unbanned</span>
                        @endif
                    </td>
                    <td style="vertical-align: middle;">
                        <a href="{{ route('admin.forum.user', [$u['id']]) }}" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-user"></i>
                        </span>
                            <span class="text text-white">
                            {{ __('forum_arckene::admin.Users.profile') }}
                        </span>
                        </a>
                    </td>
                </tr>
            @endforeach

            </tbody>
            <tfoot>
            <tr>
                <th>#</th>
                <th>{{ __('messages.Form.Pseudo') }}</th>
                <th>{{ __('messages.Form.Role') }}</th>
                <th>State</th>
                <th>Action</th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "language": {
                "url": "{{ action('Controller@datatable_lang') }}"
            },
            "columns": [
                { "width": "60px" },
                { "width": "35%" },
                null,
                { "width": "80px" },
            ]
        });
    } );
</script>