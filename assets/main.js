$('.dropdown-menu').click(function(event){
     event.stopPropagation();
});

var dragId = '';
var baseURL = 'http://summit.traction.media/index.php';
var baseNoIndex = 'http://summit.traction.media/';
var path = [];

$(document).on( 'click', '.dropdown-menu', function(e) { searchModel.preventColapse(e) } );
$(document).on( 'keyup', '#agent-search', function(e) { searchModel.findAgent(e) } );
$(document).on( 'keyup', '#client-search', function(e) { searchModel.findClient(e) } );
$(document).on( 'dragover', 'div.col-md-3', function(e) { $(this).addClass('dropTarget'); })
$(document).on( 'dragleave', 'div.col-md-3', function(e) { $(this).removeClass('dropTarget'); })
$(document).on( 'drop', 'div.col-md-3', function(e) { $(this).removeClass('dropTarget'); })
$(document).on( 'dragstart', 'article', function(e) { drag(e) })
$(document).on( 'click', '.more-btn', function(e) { taskModel.loadModal(e); })
$(document).on( 'click', '#saveModalTask', function(e) {taskModel.saveModal(); })
$(document).on( 'click', '#saveNewTask', function(e) {taskModel.saveModalNew(); })
$(document).on( 'click', '.pull-tab', function(e) {meetingModel.togglePanel(e); })
$(document).on( 'click', '.timer-start', function(e) {timerModel.toggleTimer(e); })
$(document).on( 'click', '#add-member', function(e) {taskModel.addMemberAction(e); })
$(document).on( 'click', '#add-task-btn', function(e) {taskModel.addTaskAction(e); })
$(document).on( 'click', '#add-ticket-btn', function(e) {ticketModel.addTicketAction(e); })
$(document).on( 'click', '#saveTicket', function(e) {ticketModel.saveTicket(); })

$(document).ready(function(e) {
     setBaseURL();
     var controller = path[0];
     var page = path[1];
     
     if(page == 'projectDetail') {
          if (controller == 'agent') {
               constantModel.launch();
               setBackdrop();
          }
          if (controller == 'client') {
               setClean();
               charts.initCharts();
          }    
     }
     $('[data-toggle="tooltip"]').tooltip();
     
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

function setClean() {
     $('body').css('background','#FFF');
     $('.ticket-urgency').each(function() {
          var urgency = $(this).data('urgency');
          switch(urgency) {
               case 0:
                    $(this).addClass('urgency-zero');
                    break;
               case 1:
                    $(this).addClass('urgency-one');
                    break;
               case 2:
                    $(this).addClass('urgency-two');
                    break;
               case 3:
                    $(this).addClass('urgency-three');
                    break;
               case 4:
                    $(this).addClass('urgency-four');
                    break;
               case 5:
                    $(this).addClass('urgency-five');
                    break;
          }
     })
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

     launch: function() {
          constantModel.setCurrentState();
          setInterval(function() {
               constantModel.checkForUpdate();
          }, 5000);
     },
     
     setCurrentState: function() {
          constantModel.taskArray = [];
          constantModel.projectId = $(".project-wrapper").attr('id');
          $('article.task').each(function() {
               var members = [];
               var id = $(this).attr('id');
               id = id.substring(5);
               var title = $('article#task-'+id+'>header>p.lead').html();
               var dueOn = $('article#task-'+id+'>header>span.due-on').html();
               var description = $('article#task-'+id+'>p.task-description').html();
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
               type: 'GET',
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
                    $('.save-btn').attr('id','saveModalTask');
                    $('#taskModalLabel').html(data.name);
                    $('#taskModalhiddenIdField').val(data.task_id);
                    $('#taskModaldescriptionField').val(data.description);
                    $('#taskModaldueDateField').val(data.due_on);
                    var HTML = '';
                    $.each(data.members, function(member) {
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
          $("#taskModalNameField").remove();
     },
     
     saveModal: function() {
          var taskId          = $('#taskModalhiddenIdField').val();
          var description     = $('#taskModaldescriptionField').val();
          var duedate         = $('#taskModaldueDateField').val();
          var data = {
                    task: taskId,
                    description: description,
                    due: duedate
               };
          $.ajax({
               url: baseURL + 'ajaxCommands/updateTask',
               data: data,
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
                    HTML +=            '</ul>';
                    HTML +=            '<span class="more-btn" id="more-btn-' + data.task_id + '">...</span>';
                    HTML +=     '</footer>';
                    
                    $('article#' + id).html(HTML);
                    
                    var members = $.map(data.members, function(value,index) {return [value] } );
                    $.each(members, function(data) {
                         var member = members[data];
                         HTML = '<li class="member-head" id="' + member.user_id + '">';
                         HTML +=        '<img alt="' + member.initials + '" src="' + baseNoIndex + 'assets/uploads/' + member.thumb + '">';
                         HTML += '</li>';
                         $('article#' + id + '>.members').append(HTML);
                    });
                    
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
          var activeTask = taskID;
          $.ajax({
               url:baseURL + 'ajaxCommands/getAvailableMembers',
               data: {
                    'type': 'task',
                    'id': taskID
               },
               success: function(data) {
                    var HTML = '';
                    var usr = JSON.parse(data);
                    $.each(usr, function() {
                         HTML += '<li class="member-head adder" id="' + this.id + '">';
                         HTML +=        '<img alt="' + this.initials + '" src="' + baseNoIndex + 'assets/uploads/' + this.thumb + '">';
                         HTML += '</li>';
                    });
                    $("#add-member").remove();
                    $("#saveModalTask").attr('disabled','true');
                    $("#taskModalMemberList").append(HTML);
                    $(".adder").click(function() {
                         var crewMember = $(this).attr('id');
                         $.ajax({
                              url:baseURL + 'ajaxCommands/addMember',
                              data: {
                                   'type': 'task',
                                   'id': activeTask,
                                   'member': crewMember
                              },
                              success: function(member) {
                                   member = JSON.parse(member);
                                   HTML =  '<li class="member-head" id="' + member.id + '">';
                                   HTML +=        '<img alt="' + member.initials + '" src="' + baseNoIndex + 'assets/uploads/' + member.thumb + '">';
                                   HTML += '</li>';
                                   HTML += '<li class="member-head" id="add-member" data-task="' + data.task_id + '">+</li>';
                                   $(".adder").remove();
                                   $("#taskModalMemberList").append(HTML);
                                   $("#saveModalTask").removeAttr('disabled');
                              }
                         })
                    })
               }
          })
     },
     
     addTaskAction: function(e) {
          taskModel.clearModal();
          $('.save-btn').attr('id','saveNewTask');
          $('.modal-header').append('<input type="text" value="" id="taskModalNameField" placeholder="Name" class="form-control" />');
          $('#taskModal').modal();
     },
     
     saveModalNew: function(e) {
          var taskName        = $('#taskModalNameField').val();
          var description     = $('#taskModaldescriptionField').val();
          var duedate         = $('#taskModaldueDateField').val();
          var project         = $('.project-wrapper').attr('id');
          var data = {
                    name: taskName,
                    description: description,
                    due: duedate,
                    project: project
               };
          $.ajax({
               url: baseURL + 'ajaxCommands/addTask',
               data: data,
               success: function(data) {
                    $('#taskModal').modal('hide');
                    console.log(data);
                    taskModel.appendTask(data, true);
               }
          })
     },
     
     appendTask: function(id, alert) {
          $.ajax({
               url: baseURL + 'ajaxCommands/getTask',
               data: {
                    task: id
               },
               success: function(data) {
                    var HTML = '<article class="task" draggable="true" id="task-' + data.task_id + '">'
                    HTML +=    '<header>';
                    HTML +=         '<p class="lead">' + data.name + '</p>';
                    HTML +=         '<span class="due-on ' + data.dueState + '">' + data.due_on + '</span>';
                    HTML +=     '</header>';
                    HTML +=     '<p class="task-description">' + data.description + '</p>';
                    HTML +=     '<footer>';
                    HTML +=           '<ul class="members">';
                    HTML +=            '</ul>';
                    HTML +=            '<span class="more-btn" id="more-btn-' + data.task_id + '">...</span>';
                    HTML +=     '</footer>';
                    HTML +=     '</article>';
                    
                    $('#ready-column').append(HTML);
                    
                    var members = $.map(data.members, function(value,index) {return [value] } );
                    $.each(members, function(data) {
                         var member = members[data];
                         HTML = '<li class="member-head" id="' + member.user_id + '">';
                         HTML +=        '<img alt="' + member.initials + '" src="' + baseNoIndex + 'assets/uploads/' + member.thumb + '">';
                         HTML += '</li>';
                         $('article#' + id + '>.members').append(HTML);
                    });
                    
                    if (alert) {
                         alertModel.doAlert('Task Added','success',3);
                    }
                    
               }
          })
     },

}

var ticketModel = {
     /* CLIENT FACING */
     clearModal: function() {
          $('#ticketSubjectField').val('');
          $('#ticketBodyField').val('');
          $('#ticketUrgencyField').val('');
     },
     
     addTicketAction: function(e) {
          ticketModel.clearModal();
          $('.save-btn').attr('id','saveTicket');
          $('.modal-header').append('<input type="text" value="" id="ticketSubjectField" placeholder="Subject" class="form-control" />');
          $('#ticketModal').modal();
     },
     
     saveTicket: function(e) {
          var subject         = $('#ticketSubjectField').val();
          var description     = $('#ticketBodyField').val();
          var urgency         = $('#ticketUrgencyField').val();
          var project         = $('.project-wrapper').attr('id');
          var data = {
                    subject: tasksubjectName,
                    description: description,
                    urgency: urgency,
                    project: project
               };
          $.ajax({
               url: baseURL + 'ajaxCommands/addTicket',
               data: data,
               success: function(data) {
                    $('#taskModal').modal('hide');
                    ticketModel.appendTicket(data, true);
               }
          })
     },
     
     appendTicket: function(id, alert) {
          $.ajax({
               url: baseURL + 'ajaxCommands/getTask',
               data: {
                    task: id
               },
               success: function(data) {
                    var HTML = '<article class="task" draggable="true" id="task-' + data.task_id + '">'
                    HTML +=    '<header>';
                    HTML +=         '<p class="lead">' + data.name + '</p>';
                    HTML +=         '<span class="due-on ' + data.dueState + '">' + data.due_on + '</span>';
                    HTML +=     '</header>';
                    HTML +=     '<p class="task-description">' + data.description + '</p>';
                    HTML +=     '<footer>';
                    HTML +=           '<ul class="members">';
                    HTML +=            '</ul>';
                    HTML +=            '<span class="more-btn" id="more-btn-' + data.task_id + '">...</span>';
                    HTML +=     '</footer>';
                    HTML +=     '</article>';
                    
                    $('#ready-column').append(HTML);
                    
                    var members = $.map(data.members, function(value,index) {return [value] } );
                    $.each(members, function(data) {
                         var member = members[data];
                         HTML = '<li class="member-head" id="' + member.user_id + '">';
                         HTML +=        '<img alt="' + member.initials + '" src="' + baseNoIndex + 'assets/uploads/' + member.thumb + '">';
                         HTML += '</li>';
                         $('article#' + id + '>.members').append(HTML);
                    });
                    
                    if (alert) {
                         alertModel.doAlert('Task Added','success',3);
                    }
                    
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

var charts = {
     
     taskStatusChart: {
          chart: null,
          cts: null,
          data: null,
          options: null,
          init: function() {
               charts.taskStatusChart.ctx = document.getElementById("taskStatusChart").getContext("2d");
               charts.taskStatusChart.data = [
                    {
                        value: $('#taskStatusChart').data('ready'),
                        color:'#07666A',
                        highlight: '#2C8589',
                        label: 'On Deck'
                    },
                    {
                        value: $('#taskStatusChart').data('doing'),
                        color:'#AF570B',
                        highlight: '#D4711C',
                        label: 'In Progress'
                    },
                    {
                        value: $('#taskStatusChart').data('review'),
                        color: '#AF0F0B',
                        highlight: '#D4211C',
                        label: 'In Review'
                    },
                    {
                        value: $('#taskStatusChart').data('complete'),
                        color: '#098A11',
                        highlight: '#16A71F',
                        label: 'Completed'
                    }
                ];
               charts.taskStatusChart.options = {
                    segmentShowStroke: false,
                    animationEasing: "easeOut",
                    animateScale: true,
                };
              charts.taskStatusChart.chart = new Chart(charts.taskStatusChart.ctx).Pie(charts.taskStatusChart.data,charts.taskStatusChart.options);
          }
     },
     
     ontimeStatusChart: {
          chart: null,
          ctx: null,
          data: null,
          options: null,
          init: function() {
               charts.ontimeStatusChart.ctx = document.getElementById("onTimeStatusChart").getContext("2d");
               charts.ontimeStatusChart.data = [
                    {
                         value: $('#onTimeStatusChart').data('ontime'),
                         color: '#098A11',
                         highlight: '#16A71F',
                         label: 'On Time'
                    },
                    {
                         value: $('#onTimeStatusChart').data('late'),
                         color: '#AF0F0B',
                         highlight: '#D4211C',
                         label: 'Late'
                    }
               ];
               charts.ontimeStatusChart.options = {
                    segmentShowStroke: false,
                    animationEasing: "easeOut",
                    animateScale: true,
               };
                    
               charts.ontimeStatusChart.chart = new Chart(charts.ontimeStatusChart.ctx).Pie(charts.ontimeStatusChart.data,charts.ontimeStatusChart.options);
          }
     },
     
     
     initCharts: function() {
          charts.taskStatusChart.init();
          charts.ontimeStatusChart.init();
     }
}