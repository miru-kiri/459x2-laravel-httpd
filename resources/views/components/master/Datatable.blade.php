@props(['headers','bodys'])

<div class="table-responsive">
    <table id="datatable" style="width:100%" class="table table-bordered">
        {{-- Table head --}}
        <thead @isset($headTheme) class="thead-{{ $headTheme }}" @endisset>
            <tr>
                @foreach($headers as $key => $value)
                    <th style="white-space:nowrap;">
                        {{ $value }}
                    </th>
                @endforeach
                <th></th>                            
            </tr>
        </thead>

        {{-- Table body --}}
        <tbody>
            @foreach($bodys as $id => $body)
            <tr>
                @foreach($body as $key => $td)
                    <td>
                        {{ $td }}
                    </td>
                @endforeach
                <td>
                    <button class='btn btn-warning mr-1' data-id="{{$id}}" data-toggle='modal' data-target='#modal'><i class="fas fa-edit"></i></button>
                    <button class='btn btn-danger delete-btn' data-id="{{$id}}"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
            @endforeach
        </tbody>

        <!-- {{-- Table footer --}}
        @isset($withFooter)
            <tfoot @isset($footerTheme) class="thead-{{ $footerTheme }}" @endisset>
                <tr>
                    @foreach($headers as $th)
                        <th>{{ is_array($th) ? ($th['label'] ?? '') : $th }}</th>
                    @endforeach
                </tr>
            </tfoot>
        @endisset -->
    </table>
</div>

@push('css')
<style type="text/css">
  #datatable tr td,  #datatable tr th {
        vertical-align: middle;
        text-align: center;
    }
</style>
@endpush

@push('js')
<!-- Datatableの初期化 -->
<script>
    $(document).ready(function () {
        $('#datatable').DataTable({
		  "order": [[0,"DESC"]],
          "lengthChange": false,
		  "stateSave": true,
          "buttons": ["csv","pdf", "print"]
      }).buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush