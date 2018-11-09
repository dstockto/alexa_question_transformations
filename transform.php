<?php
require_once './Question.php';

$incoming = json_decode(file_get_contents('./old_questions.json'), true);

$incomingKey = 'QUESTIONS_EN_US';
$outgoingKey = 'questions_en_US';


$questions = [];
foreach ($incoming[$incomingKey] as $questionBlock) {
    foreach ($questionBlock as $questionText => $answers) {
        $question = new Question();
        $question->setIndex(count($questions) + 1);
        $question->setQuestion($questionText);
        $question->setCorrectAnswer($answers[0]);
        foreach ($answers as $i => $answer) {
            $question->addAnswer($answer);
        }

        $questions[] = $question;
    }
}

$output               = [];
$output[$outgoingKey] = [];

/** @var Question $question */
foreach ($questions as $question) {
    $output[$outgoingKey][] = [
        'index'          => $question->getIndex(),
        'question'       => $question->getQuestion(),
        'answers'        => $question->getAnswers(),
        'correct_answer' => $question->getCorrectAnswer(),
    ];
}

file_put_contents('new_question_format.json', json_encode($output, JSON_PRETTY_PRINT));
unset($output);

$intent                  = [];
$intent['types']         = [];
$intent['name'] ['name'] = 'answers';
$intent['types'][]       = ['values' => []];

foreach ($questions as $question) {
    foreach ($question->getAnswers() as $answer) {
        $intent['types'][0]['values'][] = [
            'name' => [
                'value' => $answer,
            ],
        ];
    }
}

file_put_contents('answer_values.json', json_encode($intent, JSON_PRETTY_PRINT));
