$('.dropdown-menu').click(function(event){
     event.stopPropagation();
});

var dragId = '';

$(document).on( 'click', '.dropdown-menu', function(e) { searchModel.preventColapse(e) } );
$(document).on( 'keyup', '#agent-search', function(e) { searchModel.findAgent(e) } );
$(document).on( 'keyup', '#client-search', function(e) { searchModel.findClient(e) } );
$(document).on( 'dragover', 'div.col-md-3', function(e) { $(this).addClass('dropTarget'); })
$(document).on( 'dragleave', 'div.col-md-3', function(e) { $(this).removeClass('dropTarget'); })
$(document).on( 'drop', 'div.col-md-3', function(e) { $(this).removeClass('dropTarget'); })
$(document).on( 'dragstart', 'article', function(e) { drag(e) })
$(document).on( 'click', '.more-btn', function(e) { taskModel.loadModal(e); })
$(document).on( 'click', '#saveModalTask', function(e) {taskModel.saveModal()})

function allowDrop(ev) {
     ev.preventDefault();
}

function drag(ev) {
     dragId = ev.target.id;
}

function drop(ev) {
     ev.preventDefault();
     var task = document.getElementById(dragId);
     var target = ev.target.id;
     var targetClass = ev.target.className;
     if (targetClass == 'col-md-3 dropTarget') {
          $('#' + target).append(task);
          updateTaskState(target);
     } else {
          alertModel.doAlert('Bad Drop Area','warning',5);
     }
}

function updateTaskState(target) {
     var arr = target.split('-');
     var newState = arr[0];
     $.ajax({
          url: 'http://traction.media/summit/index.php/ajaxCommands/updateTaskStatus',
          data: {
               newState: newState,
               task: dragId
          },
          success: function() {
               alertModel.doAlert('Updated','success',3);
          }
     });
}

var taskModel = {
     loadModal: function(e) {
          var id = e.target.id;
          var arr = id.split('-');
          var taskId = arr[2];
          $.ajax({
               url: 'http://traction.media/summit/index.php/ajaxCommands/getTask',
               data: {
                    task: taskId,
               },
               success: function(data) {
                    taskModel.clearModal();
                    $('#taskModalLabel').html(data.name);
                    $('#taskModalhiddenIdField').val(data.task_id);
                    $('#taskModaldescriptionField').val(data.description);
                    $('#taskModaldueDateField').val(data.due_on);
                    $.each(data.members, function() {
                         var HTML = '<li>';
                         HTML += 'Member';
                         HTML += '</li>';
                         $('#taskModalmemberList').append(HTML);
                    })
                    $('#taskModal').modal()
               }
          })
     },
     
     clearModal: function() {
          $('#taskModalLabel').html('New Task');
          $('#taskModalhiddenIdField').val('');
          $('#taskModaldescriptionField').val('');
          $('#taskModaldueDateField').val('');
          $('#taskModalmemberList').html('');
     },
     
     saveModal: function() {
          var taskId          = $('#taskModalhiddenIdField').val();
          var description     = $('#taskModaldescriptionField').val();
          var duedate         = $('#taskModaldueDateField').val();
          $.ajax({
               url: 'http://traction.media/summit/index.php/ajaxCommands/updateTask',
               data: {
                    task: taskId,
                    description: description,
                    due: duedate
               },
               success: function(data) {
                    $('#taskModal').modal('hide');
                    taskModel.updateTask(taskId);
               }
          })
     },
     
     updateTask: function(id) {
          $.ajax({
               url: 'http://traction.media/summit/index.php/ajaxCommands/getTask',
               data: {
                    task: id
               },
               success: function(data) {
                    var HTML =  '<header>';
                    HTML +=         '<p class="lead">' + data.name + '</p>';
                    HTML +=         '<span class="due-on ' + data.dueState + '">' + data.due_on + '</span>';
                    HTML +=     '</header>';
                    HTML +=     '<p class="task-description">' + data.description + '</p>';
                    HTML +=     '<footer>';
                    HTML +=           '<ul class="members">';
                                                $.each(data.members, function(member) {
                                                        HTML += '<li class="member-head" id="' + member.user_id + '">';
                                                        HTML +=        '<img alt="' + member.initials + '" src="http://traction.media/summit/assets/uploads/' + member.thumb + '">';
                                                        HTML += '</li>';
                                                });
                    HTML +=            '</ul>';
                    HTML +=            '<span class="more-btn" id="more-btn-' + data.task_id + '">...</span>';
                    HTML +=     '</footer>';
                    
                    $('article#' + id).html(HTML);
                    alertModel.doAlert('Task Saved','success',3);
               }
          })
     }
}

var alertModel = {
     alertIndex: 0,
     
     /*
      * Type options: success, info, warning, danger
      * duration in seconds
      * short message (1 to 5 words)
      */
     doAlert: function(msg,type,duration) {
          if (alertModel.alertIndex >= 3) {
               alertModel.removeAlert(3);
               alertModel.removeAlert(2);
               alertModel.removeAlert(1);
               alertModel.removeAlert(0);
               alertModel.alertIndex = 0;
          }
          var id = alertModel.alertIndex;
          alertModel.alertIndex++;
          var t = duration * 1000;
          var HTML = '<div id="alert-' + id + '" class="alert alert-' + type + '" role="alert">' + msg + '</div>';
          $('#alert-box').append(HTML);
          setTimeout( function() { alertModel.removeAlert(id);},t);
     },
     
     removeAlert: function(id) {
          $("#alert-" + id).css('opacity','0.0');
          setTimeout(function() {$("#alert-" + id).remove();},1000);
          alertModel.alertIndex--;
     }
}


var searchModel = {

	preventColapse: function(e) { 
		e.stopPropagation();
	},

	findAgent: function(e) {
		e.stopPropagation();
	},

	findClient: function(e) {
		e.stopPropagation
	}
}