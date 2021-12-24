function set_up(n,questy_li,ques_li,opt_li,ans_li)
{
  for(var i=0;i<n;i++)
  {
    var j=i+1
    if(questy_li[i]=='Single Choice Question' || questy_li[i]=='Multiple Choice Question')
    {
      $('#question_paper').append('<input class="form-control" id="question" type="text" value="">')
      $('#question_paper').append('<input class="form-control" id="option-1" type="text"  value="">')
      $('#question_paper').append('<input class="form-control" id="option-2" type="text"  value="">')
      $('#question_paper').append('<input class="form-control" id="option-3" type="text"  value="">')
      $('#question_paper').append('<input class="form-control" id="option-4" type="text"  value="">')
      $('#question_paper').append('<input class="form-control" id="answer" type="text"  value=""><br>')
      $('#question').attr('id','question-'+j)
      $('#option-1').attr('id','option-1'+j)
      $('#option-2').attr('id','option-2'+j)
      $('#option-3').attr('id','option-3'+j)
      $('#option-4').attr('id','option-4'+j)
      $('#answer').attr('id','answer-'+j)
      $('#question-'+j).attr('value',j+')'+ques_li[i])
      $('#option-1'+j).attr('value','A)'+opt_li[i][0])
      $('#option-2'+j).attr('value','B)'+opt_li[i][1])
      $('#option-3'+j).attr('value','C)'+opt_li[i][2])
      $('#option-4'+j).attr('value','D)'+opt_li[i][3])
      $('#answer-'+j).attr('value','Answer:- '+ans_li[i])
    }
    else if(questy_li[i]=='True or False')
    {
      $('#question_paper').append('<input class="form-control" id="question" type="text" value="">')
      $('#question_paper').append('<input class="form-control" id="option-1" type="text"  value="">')
      $('#question_paper').append('<input class="form-control" id="option-2" type="text"  value="">')
      $('#question_paper').append('<input class="form-control" id="answer" type="text"  value=""><br>')
      $('#question').attr('id','question-'+j)
      $('#option-1').attr('id','option-1'+j)
      $('#option-2').attr('id','option-2'+j)
      $('#answer').attr('id','answer-'+j)
      $('#question-'+j).attr('value',j+')'+ques_li[i])
      $('#option-1'+j).attr('value','A)'+'True')
      $('#option-2'+j).attr('value','B)'+'False')
      $('#answer-'+j).attr('value','Answer:- '+ans_li[i])
    }
    else
    {
      $('#question_paper').append('<input class="form-control" id="question" type="text" value="">')
      $('#question_paper').append('<input class="form-control" id="answer" type="text"  value="">')
      $('#question').attr('id','question-'+j)
      $('#answer').attr('id','answer-'+j)
      $('#question-'+j).attr('value',j+')'+ques_li[i])
      $('#answer-'+j).attr('value','Answer:- '+ans_li[i])
    }
    func_block_input()
  }
}
function func_block_input()
{
  $('input').attr('disabled','disabled')
}
set_up(4,['Single Choice Question','Multiple Choice Question','True or False','Fill in the Blank'],
            ['Who is your DBMS instructor?','To whom are you presenting this?','Is project good?','Name of project?'],
            [['Uma mam','Mahesh sir','Manjunath sir','Pavan sir'],['Uma mam','Mahesh sir','Manjunath sir','Pavan sir'],[],[]],
            ['Uma mam','Uma mam,Manjunath sir','True','acadgenix'])
