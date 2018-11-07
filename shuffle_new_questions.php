<?php
require_once 'Question.php';

$languageKey = 'questions_en_US';

$data = json_decode(file_get_contents('new_question_format.json'), true);

$questions = [];
foreach ($data[$languageKey] as $questionData) {
    $question = new Question();
    $question->setIndex($questionData['index']);
    $question->setQuestion($questionData['question']);
    $question->setCorrectAnswer($questionData['correct_answer']);
    foreach ($questionData['answers'] as $answer) {
        $question->addAnswer($answer);
    }

    $questions[] = $question;
}

$output = [];
$output[$languageKey] = [];

/** @var Question $question */
foreach ($questions as $question) {
    $output[$languageKey][] = [
        'index'          => $question->getIndex(),
        'question'       => $question->getQuestion(),
        'answers'        => $question->getShuffledAnswers(),
        'correct_answer' => $question->getCorrectAnswer(),
    ];
}

file_put_contents('shuffled_answers.json', json_encode($output, JSON_PRETTY_PRINT));

unset($output);

$intent            = [];
$intent['name']    = 'answers';
$intent['types']   = [];
$intent['types'][] = ['values' => []];

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
