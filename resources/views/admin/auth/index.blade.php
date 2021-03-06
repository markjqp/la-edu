﻿<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    @include('admin.common.header')
    <title>权限管理</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 权限管理 <span
            class="c-gray en">&gt;</span> 权限列表 <a class="btn btn-success radius r"
                                                  style="line-height:1.6em;margin-top:3px"
                                                  href="javascript:location.replace(location.href);" title="刷新"><i
                class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
    <div class="text-c"> 日期范围：
        <input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'datemax\')||\'%y-%M-%d\'}' })" id="datemin"
               class="input-text Wdate" style="width:120px;">
        -
        <input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'datemin\')}',maxDate:'%y-%M-%d' })" id="datemax"
               class="input-text Wdate" style="width:120px;">
        <input type="text" class="input-text" style="width:250px" placeholder="输入权限名称..." id="search_manage_name"
               name="">
        <button type="submit" class="btn btn-success radius" id="searchmanage" name=""><i class="Hui-iconfont">
                &#xe665;</i> 搜权限
        </button>
    </div>
    <div class="cl pd-5 bg-1 bk-gray mt-20"><span class="l"><a href="javascript:;" onclick="manageDel()"
                                                               class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> <a
                    href="javascript:;" onclick="manage_add()" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加权限</a></span>
    </div>
    <div class="mt-20">
        <table class="table table-border table-bordered table-hover table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="5%"><input type="checkbox" name="" value=""></th>
                <th width="5%">ID</th>
                <th width="10%">权限</th>
                <th width="10%">控制器</th>
                <th width="15%">方法</th>
                <th width="15%">上级权限</th>
                <th width="15%">菜单</th>
                <th width="10%">操作</th>
            </tr>
            </thead>
            <tbody class="text-c"></tbody>
        </table>
    </div>
</div>

@include('admin.common.footer')

<script type="text/javascript">
    $(function () {
        //--表格配置start--
        $table = $('.table-sort').dataTable({
//            "aaSorting": [[1, "desc"]],//默认第几个排序
            "bStateSave": true,//状态保存
            "lengthMenu": [5, 10, 20, 50],//表格左上角可选每页显示条数
            "searching": false,//关闭本地搜索
            "serverSide": true,//开启服务器模式
//            'aocolumnDefs': [{'bSorttable':false,'aTargets':[0,7]}],//指定不接排序字段
//            'sorting':[[1,'desc']],//指定默认排序字段
            'createdRow': function (row, data) {//当每行创建的时候执行的回调函数
                var $row = $(row);//当前行对象
                //第一列加入复选框
                $row.find('td:eq(0)').html("<input type='checkbox' name='id[]' value='" + data.id + "'/>");

//                //第三列改成性别
//                if(data.gender==1){
//                    $row.find('td:eq(3)').html("男");
//                }
//                if(data.gender==2){
//                    $row.find('td:eq(3)').html("女");
//                }
//                if(data.gender==3){
//                    $row.find('td:eq(3)').html("保密");
//                }
//
//                //改变权限显示
//
//
                //第6列改成状态
                if (data.is_nav == 1) {
                    $row.find('td:eq(6)').html("<i class='Hui-iconfont'>&#xe6a7;</i>");
                } else {
                    $row.find('td:eq(6)').html("<i class='Hui-iconfont'>&#xe6a6;</i>");
                }
//                if(data.status==2){
//                    $row.find('td:eq(8)').html("禁用");
//                }

                //最后一列加入内容
                $row.find('td:last').html("<a title='编辑' href='javascript:void(0);' onclick=manage_edit('" + data.id + "') class='ml-5' style='text-decoration:none'><i class='Hui-iconfont'>&#xe6df;</i></a> " +
                    "<a title='删除' href='javascript:void(0);' onclick=manageDelOne('" + data.id + "') class='ml-5' style='text-decoration:none'><i class='Hui-iconfont'>&#xe6e2;</i></a>");

            },
            'ajax': {
                'url': "{{ url('admin/auth/index') }}",
                'type': "post",
                'data': function (data) {
                    //每页显示的数据量
                    data.pageSize = data.length;
                    //当前是第一页
                    data.page = data.start >= data.length ? Math.ceil(data.start / data.length) + 1 : 1;
                    //添加csrf值
                    data._token = "{{ csrf_token() }}";

                    //*--附加搜索条件start--*//
                    //开始日期
                    data.updated_start = $('#datemin').val();
                    //结束日期
                    data.updated_end = $('#datemax').val();
                    //权限名称
                    data.manage_name = $('#search_manage_name').val();
//                    console.log(data);
                    //*--附加搜索条件end--*//
                },
            },
            //columns是自动完成的,ajax返回的信息会被自动填充到表格中去
            'columns': [
                {'data': 'a', 'defaultContent': ""},
                {'data': 'id'},
                {'data': 'auth_name'},
                {'data': 'controller'},
                {'data': 'action'},
                {'data': 'parent_name'},
                {'data': 'is_nav'},
                {'data': 'b', 'defaultContent': 'id'},
            ],
            //"stateSave": true,//储存分页位置，每页显示的长度，过滤后的结果和排序
            "aoColumnDefs": [
                //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
                {"orderable": false, "aTargets": [0, 7]}// 制定列不参与排序
            ]
        });
        ////--表格配置end--
    });

    /*权限-添加start*/
    function manage_add() {
        var title = "添加权限";//弹窗标题
        var url = "{{ url('admin/auth/add') }}";//弹窗的地址
        var h = "510";//弹窗高度
        var w = "800";//弹窗宽度
        layer_show(title, url, w, h);
    }
    /*权限-添加end*/

    /*权限-编辑start*/
    function manage_edit(id) {
        var title = "修改权限";//弹窗标题
        var url = "{{ url('admin/auth/edit') }}/" + id ;//弹窗的地址
        var h = "500";//弹窗高度
        var w = "800";//弹窗宽度
        layer_show(title, url, w, h);
    }
    /*权限-编辑end*/

    /*权限批量-删除start*/
    function manageDel() {
        layer.confirm('确认要删除吗？', function (index) {
            //获取选中的数据的id
            var ids = [];
            $('input:checked').each(function () {
                ids.push($(this).val());
            });

            //判断是否选中
            if (ids.length < 1) {
                layer.alert('请至少选中一条数据!');
            } else {
                $.ajax({
                    type: 'post',
                    url: "{{ url('admin/auth/del') }}",
                    data: {'id': ids, '_token': "{{ csrf_token() }}"},
                    dataType: 'json',
                    success: function (data) {
                        if (data.status===1) {
                            layer.msg('已删除!', {icon: 1, time: 1000});
                            $table.api().ajax.reload();//刷新表格对象 $table是上面的table对象,往上找能够发现为自定义
                            layer.close(index);
                        } else {
                            layer.msg('删除失败!', {icon: 2, time: 1000});
                            layer.close(index);
                        }
                    },
                    error: function (data) {
                        console.log(data.msg);
                    },
                });
            }
        });
    }
    /*权限批量-删除start*/

    /*删除单个权限start*/
    function manageDelOne(id) {
        layer.confirm('do delete?', function (i) {
            $.ajax({
                'url': "{{ url('admin/auth/del') }}" ,
                'type': 'post',
                'dataType': 'json',
                'data': {'id': id, '_token': "{{ csrf_token() }}"},
                'success': function (res) {
                    if (res.status) {
                        layer.msg('delete success!', {icon: 1, time: 1000});
                        $table.api().ajax.reload();//刷新表格对象 $table是上面的table对象,往上找能够发现为自定义
                        layer.close(i);
                    } else {
                        layer.msg('delete failed!', {icon: 2, time: 1000});
                        layer.close(i);
                    }
                }
            });
        });

    }
    /*删除单个权限end*/

    /*权限-搜索start*/
    $('#searchmanage').click(function () {
        $table.api().ajax.reload();//刷新表格对象 $table是上面的table对象,往上找能够发现为自定义
    });
    /*权限-搜索start*/
</script>
</body>
</html>