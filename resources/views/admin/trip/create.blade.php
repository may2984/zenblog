@push('css')
<style type="text/css">
  .bi-trash3:hover,
  .bi-pen-fill:hover {
    cursor: pointer
  }

  .error_text {
    height: 7px;
  }
</style>
<link href='https://guillotine.js.org/css/demo.min.css' rel='stylesheet'>
<link href='//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css' rel='stylesheet'>
@endpush
@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
  $(function() {

    $.fn.makeRow = function(id, name, status, mass_delete = true) {

      var status = $.fn.getStatus(status, id);

      var dataList = `<tbody><tr id=${id}>`;

      if (mass_delete) {
        dataList += `<th class="handle"><input type='checkbox' value="${id}" class="trip_list_checkbox" name="trip_list_checkbox[]"></th>`;
      }

      dataList += `<th>${id}</th>                     
                          <td class="handle" title="Status">${name}</td>                                                                                               
                          <td class="text-center handle" title="Status">${status}</td>     
                          <td class="text-center">
                              <form id='delete_${id}' method="post" action="/admin/trip/${id}" style="display:inline;">
                              @csrf
                              <i class="bi bi-trash3" onclick="$.fn.delete(${id})" alt="Delete"></i>                                                                            
                              </form> 
                              <a href="javascript:void();" onClick="$.fn.edit(${id})">
                              <i class="bi bi-pen-fill edit-author" alt="Edit"></i>
                              </a>
                          </td>
                      </tr></tbody>`;

      return dataList;
    };

    $.fn.edit = function(id) {
      alert(`/admin/trip/${id}/edit`);
    }

    $.fn.toggleStatus = function(id, status) {

      var url = `/admin/trip/toggle/status/${status}/${id}`;

      $.ajax({
          method: 'GET',
          url: url,
          statusCode: {
            404: function() {
              alert("Unable to find the requested page");
            },
            500: function() {
              alert("Internal Server Error");
            }
          }
        })
        .done(function(response) {

          if (response.type == 'success') {

            if (status === 0) {
              var message = '{{$label}} dectivated';
            } else {
              var message = '{{$label}} activated';
            }

            $('#error-message-ajax').addClass('hidden');
            $('#success-message-ajax').removeClass('hidden').html(message);

            var status_button = $.fn.getStatus(status, id);
            $('span#status_' + id).parent('td').html(status_button)
          } else {
            $('#success-message-ajax').addClass('hidden');
            $('#error-message-ajax').removeClass('hidden').html(message);
          }

          setTimeout(function() {
            $('#success-message-ajax').addClass('hidden');
            $('#error-message-ajax').addClass('hidden');
          }, 2500);
        });
    };

    $.fn.makeListHeader = function(headerLabels) {

      var dataHeader = `<thead><tr>`;

      $.each(headerLabels, function(header_key, header_value) {

        var class_name = "";
        var width = "";

        $.each(header_value, function(key, value) {

          label = value.label;
          class_name = value.class;
          width = value.width;

          if (label == 'delete_trip') {
            dataHeader += `<th scope="col" class="${class_name}" width="${width}"><input type='checkbox' id="${label}_check_all" onClick="$.fn.checkAllTrip( $(this).prop('checked') );"></th>`;
          } else {
            dataHeader += `<th scope="col" class="${class_name}" width="${width}">${label}</th>`;
          }
        });
      });

      dataHeader += `</tr></thead>`;

      return dataHeader;
    }

    $.fn.checkAllTrip = function(checked) {
      if (checked) {
        $(".trip_list_checkbox").prop('checked', 'checked');
      } else {
        $(".trip_list_checkbox").prop('checked', '');
      }
    }

    $.fn.getList = function(url) {

      $('#list').html('<img src="{{ asset("backend/assets/img/Bars-1s-200px.gif") }}" height=50>');

      $.get(url, function(data, response) {

        $.fn.setMembersOption(data.members);  

        var headerLabels = {
          "delete": [{
            "label": "delete_trip",
            "class": "",
            "width": "5%"
          }],
          "id": [{
            "label": "Id",
            "class": "",
            "width": "5%"
          }],
          "name": [{
            "label": "Name",
            "class": "",
            "width": "50%"
          }],
          "status": [{
            "label": "Status",
            "class": "text-center",
            "width": "30%"
          }],
          "action": [{
            "label": "Action",
            "class": "text-center",
            "width": "10%"
          }]
        };

        var count = 1;

        var dataHeader = $.fn.makeListHeader(headerLabels);

        var delete_button_colspan = Object.keys(headerLabels).length;

        $.each(data.trips, function(key, value) {
          var id = value.id;
          var name = value.name
          var status = value.status;
          dataHeader += $.fn.makeRow(id, name, status);
        });
        
        $('#list').html(dataHeader);

        var deleteButton = ``;

        if (data.trips.length) {
          deleteButton += `<span class="badge badge-danger">`;
          deleteButton += `<a class="text-black" href="javascript:void(0);" onclick="$.fn.deleteAll();">Delete</a></span>`;
        }

        $('#button').html(deleteButton);

        setTimeout(function() {
          $('#success-message').fadeOut()
        }, 2500);

      });
    };

    $.fn.deleteAll = function() {

      idJson = [];

      var numberOfCheckedTrips = $(".trip_list_checkbox").filter(':checked').length;

      if (!numberOfCheckedTrips) {
        alert("Please check at least one row to delete");
        return false;
      }

      $(".trip_list_checkbox").each(function(index, value) {

        if ($(this).prop("checked")) {
          $.fn.delete(this.value);
        }

      });
      
       $.fn.massDelete( idJson );
    }

    $.fn.getStatus = function(status, id) {

      if (status == 1) {
        var badge_class = 'success'
        var toggle_status = 0;
        var status_text = 'Active';
        var text_color = 'white';
      } else {
        var badge_class = 'warning'
        var toggle_status = 1;
        var status_text = 'Inactive';
        var text_color = 'black';
      }

      return `<span class="badge bg-${badge_class}" id="status_${id}">
                  <a class="text-${text_color}" href="javascript:void(0);" onclick="$.fn.toggleStatus(${id}, ${toggle_status})">
                  ${status_text}
                  </a>
                </span`;

    };

    $.fn.getList("{{ route('trip.index') }}");

    $.fn.delete = function(id) {

      if (confirm('Are you sure you want to delete it?')) {

        var form = $("#delete_" + id);
        var csrf = form.find('input[name=_token]').val();

        $.ajax({
            method: 'DELETE',
            url: form.attr('action') + '?_token=' + csrf,
            statusCode: {
              404: function() {
                alert("Unable to find the requested page");
              },
              500: function() {
                alert("Internal Server Error");
              }
            }
          })
          .done(function(response) {

            if (response[0] == 'success') {
              $('#success-message-ajax').removeClass('hidden');
              $('#' + id).remove();
              $.fn.setIndex();
              setTimeout(function() {
                $('#success-message-ajax').addClass('hidden');
              }, 2500);
            }
            if (response[0] == 'error') {
              $('#error-message-ajax').removeClass('hidden');
              setTimeout(function() {
                $('#error-message-ajax').addClass('hidden');
              }, 2500);
            }
          });
      }
    }

    $.fn.massDelete = function(idJson) {

      if (confirm('Are you sure you want to delete it?')) {

        var csrf = form.find('input[name=_token]').val();

        console.log(idJson);

        $.ajax({
            method: 'GET',
            url: form.attr('action') + '?_token=' + csrf,
            data: idJson,
            dataType: 'json',
            statusCode: {
              404: function() {
                alert("Unable to find the requested page");
              },
              500: function() {
                alert("Internal Server Error");
              }
            }
          })
          .done(function(response) {

            if (response[0] == 'success') {
              $('#success-message-ajax').removeClass('hidden');
              $.each(idJson, function(key, value) {

                console.log(value);

                // $('#'+id).remove();

              });

              $.fn.setIndex();
              setTimeout(function() {
                $('#success-message-ajax').addClass('hidden');
              }, 2500);
            }
            if (response[0] == 'error') {
              $('#error-message-ajax').removeClass('hidden');
              setTimeout(function() {
                $('#error-message-ajax').addClass('hidden');
              }, 2500);
            }
          });
      }
    }

    $.fn.setMembersOption = function(members) {
      var option = '';
      $.each(members, function(key, value) {
        option += `<option value="${value.id}">${value.name}</option>`
      });
      $('#members').html(option);
    };

    $.fn.resetForm = function() {
      $('#form')[0].reset();
      $('#form-title').html('Add {{ $label }}');
      $('#form').attr('action', "{{ route('trip.store') }}");
    };

    $("#form").submit(function(e) {

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
      });

      e.preventDefault();
      var form = this;
      var formData = $(form).serialize()

      $('#name_error').html('');
      $('#member_error').html('');

      $.ajax({
        method: 'POST',
        url: $(form).attr('action'),
        data: formData,
        dataType: 'json',

        success: function(response) {

          if (response[0] == 'success') {

            trip = response.data[0];

            var id = response.data[0].id;
            var name = response.data[0].name;
            var status = response.data[0].status;

            $('#list').append($.fn.makeRow(id, name, status));

            $.fn.resetForm();
            $('#success-message').removeClass('hidden');
            $('#success-message').html(response.message);
            setTimeout(function() {
              $('#success-message').addClass('hidden');
            }, 3000);
          }
          if (response[0] == 'error') {
            $('#error-message').removeClass('hidden');
            $('#error-message').html(response.message);
            setTimeout(function() {
              $('#error-message').addClass('hidden');
            }, 3000);
          }
        },
        error: function(response) {
          $.each(response.responseJSON.errors, function(prefix, val) {
            var error_elm = '#' + prefix + '_error';
            $(form).find('#' + prefix + '_error').html(val);
          });
        },
        statusCode: {
          404: function() {
            alert("Unable to find the requested page");
          },
          500: function() {
            alert("Internal Server Error");
          }
        }
      });
    });

  });
</script>
@endpush

<x-admin.layout>
  <x-slot:title>Add {{$label}}</x-slot:title>
  <section class="section">
    <div class="row">
      <div class="col-lg-5">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title pb-0 mb-0" id="form-title">Add {{$label}}</h5>
            <h5 class="card-title pt-0 mb-0">
              <div class="float-end fs-8 text-success hidden" id="success-message"></div>
              <div class="float-end fs-8 text-danger hidden" id="error-message"></div>
            </h5>
            <form action="{{ route('trip.store') }}" id="form">
              <meta name="csrf-token" content="{{ csrf_token() }}">
              <div class="row mb-3"><span class="text-danger error_text" id="name_error"></span></div>
              <div class="row mb-3">
                <label for="inputPassword" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="name" value="<?php echo rand(); ?>">
                </div>
              </div>
              <div class="row mb-4"><span class="text-danger error_text" id="members_error"></span></div>
              <div class="row mb-3">
                <label for="inputPassword" class="col-sm-2 col-form-label">Members</label>
                <div class="col-sm-10">
                  <select class="form-select" multiple aria-label="multiple select" id="members" name="members[]"></select>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-sm-10">
                  <button type="submit" class="btn btn-primary">Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">{{$label}} List
              <div class="float-end fs-8 text-success hidden" id="success-message-ajax"></div>
              <div class="float-end fs-8 text-danger hidden" id="error-message-ajax"></div>
            </h5>
            <table class="table" id="list"></table>
            <div id="button"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
</x-admin.layout>