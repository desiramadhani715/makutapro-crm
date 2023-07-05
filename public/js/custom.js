$('#domisili_prov').change(function(){
    var provinsi = $(this).val();
    if(provinsi){
        $.ajax({
        type:"GET",
        url:"/getkota?province_id="+provinsi,
        dataType: 'JSON',
        success:function(res){
            if(res){
                $("#domisili_id").empty();
                $("#domisili_id").append('<option>Choose City</option>');
                $.each(res,function(id,city){
                    $("#domisili_id").append('<option value="'+id+'">'+city+'</option>');
                });
            }else{
            $("#domisili_id").empty();
            }
        }
        });
    }else{
        $("#domisili_id").empty();
    }
});

$('#work_prov').change(function(){
    var provinsi = $(this).val();
    if(provinsi){
        $.ajax({
        type:"GET",
        url:"/getkota?province_id="+provinsi,
        dataType: 'JSON',
        success:function(res){
            if(res){
                $("#work_id").empty();
                $("#work_id").append('<option>Choose City</option>');
                $.each(res,function(id,city){
                    $("#work_id").append('<option value="'+id+'">'+city+'</option>');
                });
            }else{
            $("#work_id").empty();
            }
        }
        });
    }else{
        $("#work_id").empty();
    }
});

$('#status_id').change(function(){
    var status_id = $(this).val();
    if(status_id){
        $.ajax({
        type:"GET",
        url:"/getstandard?status_id="+status_id,
        dataType: 'JSON',
        success:function(res){
            if(res){
                $("#standard_id").empty();
                $("#standard_id").append('<option>Choose Reason</option>');
                $.each(res,function(id,city){
                    $("#standard_id").append('<option value="'+id+'">'+city+'</option>');
                });
            }else{
            $("#standard_id").empty();
            }
        }
        });
    }else{
        $("#standard_id").empty();
    }
});

$('#status_id').change(function(){
    var st = $(this).val();
    console.log(st)
    if(st == 5){
        document.getElementById('reason').style.display = 'none';
        document.getElementById('closing').style.display = 'block';
    }
    if(st != 5 ){
        document.getElementById('closing').style.display = 'none';
        document.getElementById('reason').style.display = 'block';
    }
});

$('#project').change(function(){
    var project = $(this).val();
    if(project){
        $.ajax({
            type:"GET",
            url:"/get_agent?project="+project,
            dataType: 'JSON',
            success:function(res){
                if(res){
                    $("#agent").empty();
                    $("#agent").append('<option value="">Choose Agent</option>');
                    $.each(res,function(agent_id,nama_agent){
                        $("#agent").append('<option value="'+agent_id+'">'+nama_agent+'</option>');
                    });
                }else{
                $("#agent").empty();
                }
            }
        });
        $.ajax({
            type:"GET",
            url:"/get_campaign?project="+project,
            dataType: 'JSON',
            success:function(res){
                if(res){
                    $("#campaign").empty();
                    $("#campaign").append('<option value="">Choose Campaign</option>');
                    $.each(res,function(campaign_id,nama_campaign){
                        $("#campaign").append('<option value="'+campaign_id+'">'+nama_campaign+'</option>');
                    });
                }else{
                $("#campaign").empty();
                }
            }
        });
    }else{
        $("#agent").empty();
    }
});

$('#agent').change(function(){
        var agent = $(this).val();
        if(agent){
            $.ajax({
            type:"GET",
            url:"/getsales?agent="+agent,
            dataType: 'JSON',
            success:function(res){
                if(res){
                    $("#sales").empty();
                    $("#sales").append('<option value="">Choose Sales</option>');
                    $.each(res,function(sales_id,nama_sales){
                        $("#sales").append('<option value="'+sales_id+'">'+nama_sales+'</option>');
                    });
                }else{
                $("#sales").empty();
                }
            }
            });
        }else{
            $("#sales").empty();
        }
});

var cekHp = function($role){
    var hp = $("input[name=hp]").val();
    if ($role == 1) {
        var project = $("#project").val();
        var url = "/cek_hp";
    }
    if ($role == 3) {
        var url = "/sm/cek_hp";
    }
    if(hp!=""){
    //    $('.status').html("Tunggu sebentar..");
        $.ajax({
            url: url,
            type: 'GET',
            data: {hp : hp, project_id : project},
            success: function(data) {
                $('.pesan').html('');
                if(!data.status){
                    $('.pesan').html('<p class="text-danger"> Nomor hp <b>'+hp+'</b> sudah terdaftar ..! </p>');
                    $('#add').prop('disabled', true);
                }else{
                    $('#add').prop('disabled', false);
                }

            },
            error: function(e) {
                $('.pesan').html('Ada gangguan koneksi !');
            }
        });

    }
}

var rupiah = document.getElementById("rupiah");
rupiah.addEventListener("keyup", function(e) {
    rupiah.value = formatRupiah(this.value, "Rp. ");
});

/* Fungsi formatRupiah */
function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, "").toString(),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
}



