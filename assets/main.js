$('.dropdown-menu').click(function(event){
     event.stopPropagation();
});

var dragId = '';
var baseURL = 'http://summit.traction.media/index.php';
var baseNoIndex = 'http://summit.traction.media/';
var path = [];
=======

$(document).on( 'click', '.dropdown-menu', function(e) { searchModel.preventColapse(e) } );
$(document).on( 'keyup', '#agent-search', function(e) { searchModel.findAgent(e) } );
$(document).on( 'keyup', '#client-search', function(e) { searchModel.findClient(e) } );
$(document).on( 'dragover', 'div.col-md-3', function(e) { $(this).addClass('dropTarget'); })
$(document).on( 'dragleave', 'div.col-md-3', function(e) { $(this).removeClass('dropTarget'); })
$(document).on( 'drop', 'div.col-md-3', function(e) { $(this).removeClass('dropTarget'); })
$(document).on( 'dragstart', 'article', function(e) { drag(e) })
$(document).on( 'click', '.more-btn', function(e) { taskModel.loadModal(e); })
$(document).on( 'click', '#saveModalTask', function(e) {taskModel.saveModal()})
$(document).on( 'click', '.pull-tab', function(e) {meetingModel.togglePanel(e); })
$(document).on( 'click', '.timer-start', function(e) {timerModel.toggleTimer(e); })
$(document).on( 'click', '#add-member', function(e) {taskModel.addMemberAction(e); })

$(document).ready(function(e) {
     setBaseURL();
     var controller = path[0];
     var page = path[1];
     
     if(page == 'projectDetail') {
          constantModel.launch();
          setBackdrop();
     }


=======

$(document).ready(function(e) {
     constantModel.setCurrentState();
     setInterval(function() {
          constantModel.checkForUpdate();
     }, 5000);
})

function setBaseURL() {
     var local = window.location.href;
     var arr = local.split('/');
     var indexPos = arr.indexOf('index.php');
     var URL = '';
     for(var i = 0; i < indexPos; i++) {
          if (arr[i].length > 1 && arr[i] != 'http:') {
               URL += arr[i] + "/";
          }
     }
     baseNoIndex = 'http://' + URL;
     URL = 'HTTP://' + URL + "index.php/";
     for(var i = (indexPos + 1); i < arr.length; i++) {
          path.push(arr[i])
     }
     baseURL = URL
     return local;
}

function setBackdrop() {
     var backdrop = Math.floor((Math.random() * 14) + 1);
     if (backdrop > 14) {
          backdrop = 14;
     }
     $('body').css('background','linear-gradient(rgba(0,0,0,0.9),rgba(0,0,0,0.8)), url("' + baseNoIndex + 'assets/backdrops/' + backdrop + '.jpg")');
     $('body').css('-webkit-background-size', 'cover');
     $('body').css('-moz-background-size', 'cover');
     $('body').css('-o-background-size', 'cover');
     $('body').css('background-size', 'cover');
}

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
     if (targetClass == 'col-md-3 task-column dropTarget') {
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
          url: baseURL + 'ajaxCommands/updateTaskStatus',
          data: {
               newState: newState,
               task: dragId
          },
          success: function() {
               alertModel.doAlert('Updated','success',3);
          }
     });
}

var constantModel = {
     taskArray: [],
     meetingArray: [],
     noteArray: [],
     projectId: '',
     
     setCurrentState: function() {
          constantModel.projectId = $(".project-wrapper").attr('id');
          $('article.task').each(function() {
               var members = [];
               var id = $(this).attr('id');
               var title = $('article#'+id+'>header>p.lead').html();
               var dueOn = $('article#'+id+'>header>span.due-on').html();
               var description = $('article#'+id+'>p.task-description').html();
               $('article#'+id+'>ul>li.member-head').each(function() {
                    var memberId = $(this).attr('id');
                    members.push(memberId);
               })
               var task = {
                    id: id,
                    members: members,
                    title: title,
                    dueDate: dueOn,
                    description: description
               }
               constantModel.taskArray.push(task);
          });
     },
     
     checkForUpdate: function() {
          $.ajax({
               type: 'POST',
               url: baseURL + 'ajaxCommands/checkForUpdate',
               data: {
                    project: constantModel.projectId,
                    tasks: constantModel.taskArray
               },
               success: function(data) {
                    var num = 0;
                    var arr = JSON.parse(data);
                    num = arr.length;
                    if (num > 0) {
                         for(var i = 0; i<num; i++) {
                              taskModel.updateTask(arr[i], false);
                         }
                         constantModel.setCurrentState();
                    }
               }
          })
     }
}

var taskModel = {
     loadModal: function(e) {
          var id = e.target.id;
          var arr = id.split('-');
          var taskId = arr[2];
          $.ajax({
               url: baseURL + 'ajaxCommands/getTask',
               data: {
                    task: taskId,
               },
               success: function(data) {
                    taskModel.clearModal();
                    $('#taskModalLabel').html(data.name);
                    $('#taskModalhiddenIdField').val(data.task_id);
                    $('#taskModaldescriptionField').val(data.description);
                    $('#taskModaldueDateField').val(data.due_on);
                    var HTML = '';
                    $.each(data.members, function(member) {
                         console.log(data);
                            HTML += '<li class="member-head" id="' + this.user_id + '">';
                            HTML +=        '<img alt="' + this.initials + '" src="' + baseNoIndex + 'assets/uploads/' + this.thumb + '">';
                            HTML += '</li>';
                    });
                    HTML += '<li class="member-head" id="add-member" data-task="' + data.task_id + '">+</li>';
                    $("#taskModalMemberList").html(HTML);
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
               url: baseURL + 'ajaxCommands/updateTask',
               data: {
                    task: taskId,
                    description: description,
                    due: duedate
               },
               success: function(data) {
                    $('#taskModal').modal('hide');
                    taskModel.updateTask(taskId, true);
               }
          })
     },
     
     updateTask: function(id,alert) {
          $.ajax({
               url: baseURL + 'ajaxCommands/getTask',
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
                                                        HTML +=        '<img alt="' + member.initials + '" src="' + baseNoIndex + 'assets/uploads/' + member.thumb + '">';
                                                        HTML += '</li>';
                                                });
                    HTML +=            '</ul>';
                    HTML +=            '<span class="more-btn" id="more-btn-' + data.task_id + '">...</span>';
                    HTML +=     '</footer>';
                    
                    $('article#' + id).html(HTML);
                    if (alert) {
                         alertModel.doAlert('Task Saved','success',3);
                    }
                    
               }
          })
     },
     
     addMemberAction: function(e) {
          var el = $("#" + e.target.id );
          var task = el.data('task');
          taskModel.doMoreMembersList(task, el);
     },
     
     doMoreMembersList: function(taskID, el) {
          $.ajax({
               url:baseURL + 'ajaxCommands/getAvailableMembers',
               data: {
                    'type': 'task',
                    'id': taskID
               },
               success: function(data) {
                    var HTML = '';
                    var usr = JSON.parse(data);
                    console.log(usr);
                    HTML += '<ul>';
                    $.each(usr, function() {
                         HTML += '<li class="member-head adder" id="' + this.user_id + '">';
                         HTML +=        '<img alt="' + this.initials + '" src="' + this.thumb + '">';
                         HTML += '</li>';
                    });
                    HTML += '</ul>';
                    $("#add-member").html(HTML);
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
<<<<<<< HEAD
}

var meetingModel = {

     togglePanel: function(e) {
          var el = $( "#" + e.target.id);
          var target = el.data('target');
          var state = el.data('state');
          if(state == 'closed') {
               $('#' + target).css('right',0);
               el.data('state','open');
               return;
          }
          if(state == 'open') {
               $('#' + target).css('right','-300px');
               el.data('state','closed');
               return;
          }
     }
}

var timerModel = {
     
     toggleTimer: function(e) {
          var el = $( "#" + e.target.id);
          var arr = e.target.id.split('-');
          var task = arr[1];
          if (el.hasClass('timer-running')) {
               timerModel.saveEndTime(task);
               el.removeClass('timer-running');
          } else {
               timerModel.saveStartTime(task);
               el.addClass('timer-running');
          }
     },
     
     saveStartTime: function(id) {
          $.ajax({
               url: baseURL + 'ajaxCommands/saveStartTimer',
               data: {
                    task: id
               },
               success: function() {
                    alertModel.doAlert('Timer Started','success',3);
               }
          })
     },
     
     saveEndTime: function(id) {
          var now = Date.now();
          $.ajax({
               url: baseURL + 'ajaxCommands/saveEndTimer',
               data: {
                    task: id
               },
               success: function() {
                    alertModel.doAlert('Timer Stopped','success',3);
               }
          })
     }
}
