@props(['formColums','selectColums','formatFetchData','defaultUrl','defaultColums','rules'])

<div class="modal fade" id="modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="createForm" novalidate="novalidate" method="post" action="{{$defaultUrl}}/create">
            @csrf
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                                @foreach($formColums as $formColum)
                                    <div class="form-group">
                                        <label>{{ $formColum['label'] }}</label>
                                        @if($formColum['type'] == 'text')
                                            <input type="{{ $formColum['type'] }}" name="{{ $formColum['name'] }}" class="form-control" id="{{ $formColum['name'] }}" placeholder="{{ $formColum['label'] }}">
                                        @endif
                                        @if($formColum['type'] == 'textarea')
                                            <textarea name="{{ $formColum['name'] }}" class="form-control" id="{{ $formColum['name'] }}" placeholder="{{ $formColum['label'] }}" rows="5"> </textarea>
                                        @endif
                                        @if($formColum['type'] == 'select')
                                        <!-- select2-single -->
                                            <select class="form-control" name="{{$formColum['name']}}" id="{{$formColum['name']}}">
                                            @foreach($selectColums[$formColum['name']] as $selectColum)
                                                <option value="{{$selectColum['value']}}">{{$selectColum['name']}}</option>
                                            @endforeach
                                            </select>
                                        @endif
                                        @if($formColum['type'] == 'switch')
                                        <div class="form-check ml-2 pb-3">
                                            <input class="form-check-input checkbox" type="checkbox" id="{{ $formColum['name'] }}-check">
                                            <input type="hidden" name="{{ $formColum['name'] }}" value="0" id="{{ $formColum['name'] }}">
                                        </div>
                                        @endif
                                        @if($formColum['type'] == 'multiySelect')
                                            <br><select class="form-control select2" name="{{$formColum['name']}}" id="{{$formColum['name']}}">
                                            @foreach($selectColums[$formColum['name']] as $selectColum)
                                                <option value="{{$selectColum['value']}}">{{$selectColum['name']}}</option>
                                            @endforeach
                                            </select>
                                        @endif
                                        @if($formColum['type'] == 'color')
                                            <input type="{{ $formColum['type'] }}" name="{{ $formColum['name'] }}" class="form-control-color" id="{{ $formColum['name'] }}" placeholder="{{ $formColum['label'] }}">
                                        @endif
                                    </div>
                                @endforeach
                            <input type="text" name="id" id="id" value=0 hidden/>            
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                
                <button type="button" class="btn btn-default" data-dismiss="modal">戻る</button>
                <button type="submit" class="btn btn-primary">保存</button>
                <!-- <button type="button" class="btn btn-primary" id="modal-submit-btn">保存</button> -->
            </div>
        </form>
        </div>
    </div>
</div>

@push('css')
<style type="text/css">
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        /* background-color: #007bff; */
        background-color: white;
        color: black;
    }
    input[type="checkbox"]{
        transform: scale(1.5);
    }
</style>
@endpush

@push('js')
<!-- Datatableの初期化 -->
<script>
    const fetchData = @json($formatFetchData); //JSONデコード 
    const defaultUrl = @json($defaultUrl);
    const defaultColums = @json($defaultColums);
    //select2
    $('.select2').select2({
        multiple: true, //複数選択可能
        placeholder: "選択なし", //プレースホルダーを設定
        allowClear: true, //下矢印を消去
        closeOnSelect: false, //選択するたびに閉じないよう設定
        language: 'ja' // Select2のメッセージに使用する言語を指定
    });
    // $('.select2-single').select2({
    //     language: 'ja' // Select2のメッセージに使用する言語を指定
    // });
    /**
     * modalのイベント
     * show.bs.modal ： モーダルが開くとき (showメソッドを呼び出し時)
     * shown.bs.modal ： モーダルが完全に表示されたとき
     * hide.bs.modal ： モーダルが閉じるとき (hideメソッドを呼び出し時)
     * hidden.bs.modal ： モーダルが完全に閉じたとき
     */ 
    $('#modal').on('show.bs.modal', function (event) {
        //モーダルを開いたボタンを取得
        const clickItems = $(event.relatedTarget);
        let id = clickItems.data('id');
         // フォームの検証をリセットする
        $("#createForm").validate().resetForm();
        // AdminLTEのバリデーションスタイルをリセットする
        $("#createForm").find(".is-invalid").removeClass('is-invalid');
        $('#id').val(id);
        if(id == 0) {
            $('.modal-header').removeClass('bg-warning');
            $('.modal-header').addClass('bg-primary');
            $('#modal-title').text('新規登録');
            for (let key in defaultColums) {
                switch (key){
                    //select2
                    case 'site':
                        $(`#${key}`).val('').trigger('change');
                        break;
                    case 'area_id':
                        $(`#${key}`).val('').trigger('change');
                        break;
                    case 'genre_id':
                        $(`#${key}`).val('').trigger('change');
                        break;
                    case 'is_public':
                        $(`#${key}`).val(0);
                        $(`#${key}-check`).prop("checked", false); 
                        break;
                    case 'is_externally_server':
                        $(`#${key}`).val(0);
                        $(`#${key}-check`).prop("checked", false); 
                        break;
                    case 'is_https':
                        $(`#${key}`).val(0);
                        $(`#${key}-check`).prop("checked", false); 
                        break;
                    case 'is_notification':
                        $(`#${key}`).val(0);
                        $(`#${key}-check`).prop("checked", false); 
                        break;
                    case 'color':
                        $(`#${key}`).val('#f1747e');
                        break;
                    default:
                        $(`#${key}`).val(defaultColums[key]);
                }
                
            }
            
        } else {
            $('.modal-header').removeClass('bg-primary');
            $('.modal-header').addClass('bg-warning');
            $('#modal-title').text('編集');
            const editData = fetchData[id];
            Object.keys(editData).forEach((key,val) => {
                switch (key){
                    //select2
                    case 'site':
                        $(`#${key}`).val(editData[`${key}`]).trigger('change');
                        break;
                    case 'area_id':
                        $(`#${key}`).val(editData[`${key}`]).trigger('change');
                        break;
                    case 'genre_id':
                        $(`#${key}`).val(editData[`${key}`]).trigger('change');
                        break;
                    case 'is_public':
                        $(`#${key}-check`).prop("checked", editData[`${key}`] == 1 ? true : false);
                        $(`#${key}`).val(editData[`${key}`]);    
                        break;
                    case 'is_externally_server':
                        $(`#${key}-check`).prop("checked", editData[`${key}`] == 1 ? true : false);
                        $(`#${key}`).val(editData[`${key}`]);    
                        break;
                    case 'is_https':
                        $(`#${key}-check`).prop("checked", editData[`${key}`] == 1 ? true : false);
                        $(`#${key}`).val(editData[`${key}`]);    
                        break;
                    case 'is_notification':
                        $(`#${key}-check`).prop("checked", editData[`${key}`] == 1 ? true : false);
                        $(`#${key}`).val(editData[`${key}`]);    
                        break;
                    case 'color':
                        if(editData[`${key}`]) {
                            $(`#${key}`).val(editData[`${key}`]);    
                        } else {
                            $(`#${key}`).val('#f1747e');
                        }
                        break;
                    default:
                        $(`#${key}`).val(editData[`${key}`]);    
                }
                
            })
            // console.log(editData)
        }
    });
    $('#modal').on('hidden.bs.modal', function (event) {
        //モーダルを開いたボタンを取得
        const clickItems = $(event.relatedTarget);
        const id = clickItems.data('id');
        $('#id').val();
    });
    $('.delete-btn').click(function(event) {
        //モーダルを開いたボタンを取得
        const id = $(this).data('id');
        if(!confirm(`ID:${id}番を本当に削除しますか？`)) {
            return;
        }
        const url = defaultUrl + `/${id}`;
        const parameter = {"id":id,"_method": "DELETE"} 
        requestEvent('POST',url,parameter);
        // alert(`${deleteData}を本当に削除しますか？`);
    });
    // $('#is_public-check').on('click', function(event) {
        // if($('input[id="is_public-check"]').prop('checked') ) {
          //   $('#is_public').val(1);
        // } else {
           //  $('#is_public').val(0);
       //  }
    // });
    $('.checkbox').on('click', function(event) {
        let id = $(this).attr('id');
        if($(`input[id="${id}"]`).prop('checked') ) {
            $(`#${id.replace('-check', '')}`).val(1);
        } else {
            $(`#${id.replace('-check', '')}`).val(0);
        }
    });
    $('#is_notification-check').on('click', function(event) {
        if($('input[id="is_notification-check"]').prop('checked') ) {
            $('#is_notification').val(1);
        } else {
            $('#is_notification').val(0);
        }
    });
    //遅いから一旦
    $(function () {
        $.validator.setDefaults({
            submitHandler: function () {
                for (let key in defaultColums) {
                    defaultColums[key] = $(`#${key}`).val();
                }
                requestEvent('POST',defaultUrl,defaultColums);
            }
        });
        $('#createForm').validate({
            rules: @json($rules),
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
    function requestEvent(method,url,data) {
        $.ajax({
            url: url,
            type: method,
            dataType: "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            // 渡したいデータを指定
            data: data
        })
        .done(function (data, status, jqXHR) {
            if(data.result == 0) {
                // 正常レスポンス
                toastr.success(data.message)
                pageLoading()
            } else {
                // 失敗レスポンス
                console.log(data.message)
                toastr.error(data.message)
                // toastr.error('処理に失敗しました')                
            }
        })
        .fail(function (jqXHR, status, erorThrown) {
            toastr.error('処理が失敗しました')
            console.log(status);// errorとでる
        });
    }
    function pageLoading() {
        setTimeout(function () {
            location.reload();
        }, 2000)
    }
</script>
@endpush