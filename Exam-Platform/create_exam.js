var question_add_button=$('#button_add')
question_add_button.on('click',on_click_add_question)
var question_type=$('#sel1').val()
var question_submit_button=$('#question_but_for')
question_submit_button.on('click',on_click_submit_button)
var modify_saved=$('#modify_but')
modify_saved.on('click',function(){
  $('#opt-3').removeAttr('disabled');
  $('#opt-4').removeAttr('disabled');
  $('#opt-1').removeAttr('disabled');
  $('#opt-2').removeAttr('disabled');
  $('#Question').removeAttr('disabled');
  $('#answer_add').removeAttr('disabled');
  question_submit_button.prop('disabled',true);
})
var save_button=$('#save_but')
save_button.on('click',function(){

  if(!(check_question()))
  {
    question_submit_button.prop('disabled',true)
    $('#warn').text('Warning: Please Fill All Input Fields')
  }
  else if(!(check_options([$('#opt-1').val(),$('#opt-2').val(),$('#opt-3').val(),$('#opt-4').val()])))
  {
    question_submit_button.prop('disabled',true)
    $('#warn').text('Warning: Please Fill All Input Fields')
  }
  else if(!check_answer_add($('#answer_add').val(),[$('#opt-1').val(),$('#opt-2').val(),$('#opt-3').val(),$('#opt-4').val()],$('#sel1').val()))
  {
    question_submit_button.prop('disabled',true)
    $('#warn').text('Warning: Please Enter Valid Input Fields')
  }
  else
  {
    $('#opt-3').attr('disabled','disabled');
    $('#opt-4').attr('disabled','disabled');
    $('#opt-1').attr('disabled','disabled');
    $('#opt-2').attr('disabled','disabled');
    $('#Question').attr('disabled','disabled');
    $('#answer_add').attr('disabled','disabled');
    $('#warn').text('Enter Question Here:')
    question_submit_button.prop('disabled',false)
  }
})
var cancel_button=$('#cancel_but')
cancel_button.on('click', function()
{
  $('.question_format').css('display','none')
  $('#Question').val('');
  $('#opt-1').val('')
  $('#opt-2').val('')
  $('#opt-3').val('')
  $('#opt-4').val('')
  $('#answer_add').val('');
  $('#sel1').removeAttr('disabled')
})
function on_click_add_question()
{
  question_submit_button.prop('disabled',true)
  $('.question_format').css('display','inline')
  var question_type=$('#sel1').val()
  $('#sel1').attr('disabled','disabled')
  form_edit(question_type)
}
function form_edit(type)
{
  if(type=="True or False")
  {
    $('#opt-3').attr('disabled','disabled');
    $('#opt-4').attr('disabled','disabled');
    $('#opt-1').attr('disabled','disabled');
    $('#opt-2').attr('disabled','disabled');
    $('#opt-1').val("True")
    $('#opt-2').val("False")
    $('#Question').removeAttr('disabled');
    $('#answer_add').removeAttr('disabled');
    $('#answer_add').attr('placeholder','Enter True or False:')
  }
  else if(type=="Fill in the Blank")
  {
    $('#opt-3').attr('disabled','disabled');
    $('#opt-4').attr('disabled','disabled');
    $('#opt-1').attr('disabled','disabled');
    $('#opt-2').attr('disabled','disabled');
    $('#Question').removeAttr('disabled');
    $('#answer_add').removeAttr('disabled');
    $('#answer_add').attr('placeholder','Enter Answer Here:')
  }
  else
  {
    $('#opt-3').removeAttr('disabled');
    $('#opt-4').removeAttr('disabled');
    $('#opt-1').removeAttr('disabled');
    $('#opt-2').removeAttr('disabled');
    $('#Question').removeAttr('disabled');
    $('#answer_add').removeAttr('disabled');
    $('#answer_add').attr('placeholder','Enter Correct Option Here:')
  }
}
function check_question()
{
  if($('#Question').val().length==0)
  {
    return false;
  }
  else
  {
    return $('#Question').val();
  }
}
function check_options(opt_arr)
{
  if($('#sel1').val()=='Fill in the Blank')
  {
    return true;
  }
  for(var i of opt_arr)
  {
    if(i.length==0)
    {
      return false;
    }
    else
    {
      return opt_arr;
    }
  }
}
function check_answer_add(ans,opt,type)
{
  if(type=='Single Choice Question')
  {
    var flag=false;
    for(var i=0;i<opt.length;i++)
    {
      if(ans==opt[i])
      {
        flag=true;
        return i+1;
      }
    }
    return flag;
  }
  else if(type=='Multiple Choice Question')
  {
    if(ans.length==0)
    {
      return false;
    }
    else
    {
      ans=ans.split(',')
      var ans_li=[]
      flag=[]
      for(var i=0;i<ans.length;i++)
      {
        for(var j=0;j<opt.length;j++)
        {
          if(ans[i]==opt[j])
          {
            flag.push(true)
            ans_li.push(i+1)
          }
        }
      }
      if(flag.length==0)
      {
        return false;
      }
      else
      {
        return ans_li;
      }
    }
  }
  else if(type=='True or False')
  {
    if(ans=='True')
    {
      return 'True'
    }
    else if(ans=='False')
    {
      return 'False'
    }
    else
    {
      return false
    }
  }
  else
  {
    if(ans.length==0)
    {
      return false
    }
    return ans;
  }
}
function on_click_submit_button()
{
  var extract_question_type=question_type;
  var extract_question=check_question();
  var extract_options=check_options([$('#opt-1').val(),$('#opt-2').val(),$('#opt-3').val(),$('#opt-4').val()])
  var extract_answer_add=check_answer_add($('#answer_add').val(),[$('#opt-1').val(),$('#opt-2').val(),$('#opt-3').val(),$('#opt-4').val()],$('#sel1').val());
  console.log(extract_question_type)
  console.log(extract_question);
  console.log(extract_options);
  console.log(extract_answer_add);
  $('.question_format').css('display','none')
  $('#Question').val('');
  $('#opt-1').val('')
  $('#opt-2').val('')
  $('#opt-3').val('')
  $('#opt-4').val('')
  $('#answer_add').val('');
  $('#sel1').removeAttr('disabled')
  alert('Question Added Succesfully')
}
$('#modify_paper').on('click',function()
{
   $("[id^='question-']").removeAttr('disabled')
   alert('Click On Question To Modify It')
   $("[id^='question-']").click(function()
   {
     $("[id^='question-']").attr('disabled',true)
     var q=this.value[0]
     var opt_exp='option-'+q
     var ans_exp='answer-'+q
     var opt_arr=[$("[id^="+'option-1'+q+']').val(),$("[id^="+'option-2'+q+']').val(),$("[id^="+'option-3'+q+']').val(),$("[id^="+'option-4'+q+']').val()]
     modify_paper('Fill in the Blank',this.value,opt_arr,$("[id^="+ans_exp+']').val())
   })
})
function modify_paper(ques_type,ques,opts,ans)
{
  if(ques_type=='True or False')
  {
    opts[2]=''
    opts[3]=''
    $('#opt-3').attr('disabled','disabled');
    $('#opt-4').attr('disabled','disabled');
    $('#opt-1').attr('disabled','disabled');
    $('#opt-2').attr('disabled','disabled');
  }
  if(ques_type=='Fill in the Blank')
  {
    opts[2]=''
    opts[3]=''
    opts[0]=''
    opts[1]=''
    $('#opt-3').attr('disabled','disabled');
    $('#opt-4').attr('disabled','disabled');
    $('#opt-1').attr('disabled','disabled');
    $('#opt-2').attr('disabled','disabled');
  }
  if(ques_type=='Single Choice Question' || ques_type=='Multiple Choice Question')
  {
    $('#opt-3').removeAttr('disabled');
    $('#opt-4').removeAttr('disabled');
    $('#opt-1').removeAttr('disabled');
    $('#opt-2').removeAttr('disabled');
    $('#Question').removeAttr('disabled');
    $('#answer_add').removeAttr('disabled');
  }
  $('.question_format').css('display','inline')
  $('#sel1').val(ques_type);
  $('#Question').val(ques.slice(2,));
  $('#opt-1').val(opts[0].slice(2,))
  $('#opt-2').val(opts[1].slice(2,))
  $('#opt-3').val(opts[2].slice(2,))
  $('#opt-4').val(opts[3].slice(2,))
  $('#answer_add').val(ans.slice(9,));
  $('#Question').removeAttr('disabled');
  $('#answer_add').removeAttr('disabled');
}
