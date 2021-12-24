$(document).ready(function()
{
    $searchParams = new URLSearchParams(window.location.search);
    $examID = $searchParams.get('examID');
    if(!$("#exam").html().includes("AlreadyAnswered"))
    {
        var quizData = function(){
            var tmp = null;
            $.ajax({
                'async': false,
                url:"utilities/fetchQuestions.php",
                method:"POST",
                data: {id:$examID},
                datatype:"JSON",
                success: function(result){
                    tmp = JSON.parse(result);
                }
            });
            return tmp;
        }();
        const questionEl = document.getElementById("question");
        const quiz = document.getElementById("quiz");
        const a_text = document.getElementById("a_text");
        const b_text = document.getElementById("b_text");
        const c_text = document.getElementById("c_text");
        const d_text = document.getElementById("d_text");
        const answersEls = document.querySelectorAll(".answer");
        const submitBtn = document.getElementById("submit");
        let currentQuiz = 0;
        loadQuiz();
        function loadQuiz()
        {
            deselectAnswers();
            questionEl.innerHTML = quizData["question"][currentQuiz];
            a_text.innerText = quizData["optionA"][currentQuiz];
            b_text.innerText = quizData["optionB"][currentQuiz];
            c_text.innerText = quizData["optionC"][currentQuiz];
            d_text.innerText = quizData["optionD"][currentQuiz];
        }
        function getSelected()
        {
            let answer = undefined;
            answersEls.forEach((answersEl) => {
                if (answersEl.checked) {
                    answer = answersEl.id;
                }
            });
            return answer;
        }
        function deselectAnswers()
        {
            answersEls.forEach((answersEl) => {
                if (answersEl.checked)
                {
                    answersEl.checked = false;
                }
            });
        }
        const interval = setInterval(() => {
            var currentDate = (new Date()).getTime();
            if(Date.parse($("#examEndTime").text().replace(" ", "T"))<currentDate)
            {
                $.post("utilities/submitAnswers.php", {answers:answers, examID:$examID});
                quiz.innerHTML = `<h2>Time's up! Your answers are submitted. Your score will be soon out on the Examinations Page!</h2> <button onclick="location = 'https://acadgenix.ga/assignment.php'">Go to Examinations Page</button>`;
                clearInterval(interval);
            }
        }, 250);
        var answers=[];
        submitBtn.addEventListener("click", () => {
            const answer = getSelected();
            if(answer)
            {
                answers.push(answer);
                currentQuiz++;
                if(currentQuiz<quizData["question"].length)
                {
                    loadQuiz();
                }
                else
                {
                    $.post("utilities/submitAnswers.php", {answers:answers, examID:$examID});
                    quiz.innerHTML = `<h2>Your answers are submitted. Your score will be soon out on the Examinations Page!</h2> <button onclick="location = 'https://acadgenix.ga/assignment.php'">Go to Examinations Page</button>`;
                }
            }
        });
    }
});