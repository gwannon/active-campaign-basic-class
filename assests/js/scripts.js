jQuery(document).ready(function() {
  jQuery("#status-refresh").click(function(event) {
    event.preventDefault();
    checkStatus();
  });
  
  jQuery("#info-general,#info-logs,#info-tags,#info-errors,#info-general,#info-crons").click(function(event) {
    event.preventDefault();
    infoZone(jQuery(this).attr("id"));
  });

  jQuery("#migration").click(function(event) {
    event.preventDefault();
    changeStatusMigration();
  });
  
  jQuery("#start-migration").click(function(event) {
    event.preventDefault();
    jQuery("#start-migration").attr("disabled", true);
    jQuery("#migration-zone").attr("src", "/ajax.php");
    setTimeout(function() {
      jQuery("#migration-zone").attr("src", "/script_update.php");
      if(jQuery("#migration-zone").css("display") == 'none') jQuery("#migration-zone").fadeIn();
    }, 500);
  });

  jQuery('#migration-zone').load(function(){
    jQuery("#start-migration").attr("disabled", false);
  });

  checkStatus();
  infoZone("info-general");
});

function checkStatus() {
  jQuery.ajax({
    url : './ajax.php',
    data : {action: 'check-status'},
    type : 'GET',
    dataType : 'json',
    beforeSend: function () {
      jQuery("#status-refresh,#migration").attr("disabled", true);
      jQuery("#status-migration span,#status-apiac span,#status-mssql span").html("...");
      jQuery("#status-mssql,#status-apiac,#status-migration").removeClass("statusOk");
      jQuery("#status-mssql,#status-apiac,#status-migration").removeClass("statusError");
    },
    success : function(json) {
      if(json.api) {
        jQuery("#status-apiac span").html("OK");
        jQuery("#status-apiac").addClass("statusOk");
      } else {
        jQuery("#status-apiac span").html("ERROR");
        jQuery("#status-apiac").addClass("statusError");
      }
      if(json.migration) {
        jQuery("#status-migration span").html("ENCENDIDA");
        jQuery("#status-migration").addClass("statusOk");
      } else {
        jQuery("#status-migration span").html("APAGADA");
        jQuery("#status-migration").addClass("statusError");
      }
      if(json.mssql) {
        jQuery("#status-mssql span").html("OK");
        jQuery("#status-mssql").addClass("statusOk");
      } else {
        jQuery("#status-mssql span").html("ERROR");
        jQuery("#status-mssql").addClass("statusError");
      }
    },
    error : function(xhr, status) {
      alert('Disculpe, existió un problema checkStatus()');
    },
    complete : function(xhr, status) {
      jQuery("#status-refresh,#migration").attr("disabled", false);
    }
  });
}

function changeStatusMigration() {
  jQuery.ajax({
    url : './ajax.php',
    data : {action: 'change-status-migration'},
    type : 'GET',
    dataType : 'json',
    beforeSend: function () {
      jQuery("#status-refresh,#migration").attr("disabled", true);
    },
    success : function(json) { },
    error : function(xhr, status) {
      alert('Disculpe, existió un problema changeStatusMigration()');
    },
    complete : function(xhr, status) {
      jQuery("#status-refresh,#migration").attr("disabled", false);
      checkStatus();
    }
  });
}

function infoZone(action) {
  jQuery.ajax({
    url : './ajax.php',
    data : {action: action},
    type : 'GET',
    dataType : 'json',
    beforeSend: function () {
      jQuery("#info-general,#info-tags,#info-logs,#info-general,#info-errors,#info-crons").attr("disabled", true);
      jQuery("table#info-zone > thead,table#info-zone > tbody").empty();
    },
    success : function(json) {
      if (action == 'info-tags') {
        json.forEach(function(data, index) {
          if (index == 0) {
            jQuery("table#info-zone > thead").append("<tr>"+
              "<th>"+data.id+"</th>"+
              "<th>"+data.tag+"</th>"+
              "<th>"+data.count+"</th>"+
              "<th>"+data.date+"</th>"+
              "</tr>");
          } else {
            jQuery("table#info-zone > tbody").append("<tr>"+
              "<td>"+data.id+"</td>"+
              "<td>"+data.tag+"</td>"+
              "<td>"+data.count+"</td>"+
              "<td>"+data.date+"</td>"+
              "</tr>");
          }
        });
      } else if (action == 'info-logs') {
        json.forEach(function(data, index) {
          if (index == 0) {
            jQuery("table#info-zone > thead").append("<tr>"+
              "<td>"+data.date+"</td>"+
              "<td>"+data.apicall+"</td>"+
              "<td>"+data.method+"</td>"+
              "<td>"+data.payload+"</td>"+
              "</tr>");
          } else {
            jQuery("table#info-zone > tbody").append("<tr>"+
              "<td>"+data.date+"</td>"+
              "<td>"+data.apicall+"</td>"+
              "<td>"+data.method+"</td>"+
              "<td>"+data.payload+"</td>"+
              "</tr>");
          }
        });
      } else if (action == 'info-errors') {
        json.forEach(function(data, index) {
          if (index == 0) {
            jQuery("table#info-zone > thead").append("<tr>"+
              "<td>"+data.date+"</td>"+
              "<td>"+data.apicall+"</td>"+
              "<td>"+data.method+"</td>"+
              "<td>"+data.payload+"</td>"+
              "<td>"+data.httpcode+"</td>"+
              "<td>"+data.response+"</td>"+
              "</tr>");
          } else {
            jQuery("table#info-zone > tbody").append("<tr>"+
              "<td>"+data.date+"</td>"+
              "<td>"+data.apicall+"</td>"+
              "<td>"+data.method+"</td>"+
              "<td>"+data.method+"</td>"+
              "<td>"+data.httpcode+"</td>"+
              "<td>"+data.response+"</td>"+
              "</tr>");
          }
        });
      }  else if (action == 'info-general') {
        json.forEach(function(data, index) {
          if (index == 0) {
            jQuery("table#info-zone > thead").append("<tr>"+
              "<td>"+data.field+"</td>"+
              "<td>"+data.value+"</td>"+
              "</tr>");
          } else {
            jQuery("table#info-zone > tbody").append("<tr>"+
              "<td>"+data.field+"</td>"+
              "<td>"+data.value+"</td>"+
              "</tr>");
          }
        });
      }  else if (action == 'info-crons') {
        json.forEach(function(data, index) {
          if (index == 0) {
            jQuery("table#info-zone > thead").append("<tr>"+
              "<td>"+data.line+"</td>"+
              "</tr>");
          } else {
            jQuery("table#info-zone > tbody").append("<tr>"+
              "<td>"+data.line+"</td>"+
              "</tr>");
          }
        });
      }
    },
    error : function(xhr, status) {
      alert('Disculpe, existió un problema infoZone()');
    },
    complete : function(xhr, status) {
      jQuery("html, body").animate({ scrollTop: jQuery("table#info-zone").offset().top }, 800);
      jQuery("#info-general,#info-logs,#info-tags,#info-errors,#info-general,#info-crons").not("#"+action).attr("disabled", false);
      jQuery("#"+action).attr("disabled", true);
    }
  });
}