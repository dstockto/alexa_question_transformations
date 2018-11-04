<?php
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

$types = [];
$types['types'] = [];
$types['types'][] = ['values' => []];

foreach ($questions as $question) {
    foreach ($question->getAnswers() as $answer) {
        $types['types'][0]['values'][] = [
            'name' => [
                'value' => $answer,
            ],
        ];
    }
}

file_put_contents('answer_values.json', json_encode($types, JSON_PRETTY_PRINT));


//{"types": [
//        {
//            "values": [
//            {
//                "name": {
//                "value": "fox"
//              }
//            },
//            {
//                "name": {
//                "value": "wolf"
//              }
//            }
//            ]
//            }
//            ]
//}

class Question
{
    private $index;
    private $question;
    private $answers = [];
    private $correctAnswer;

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param mixed $index
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * @return array
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param array $answers
     */
    public function addAnswer($answer)
    {
        $this->answers[] = $answer;
    }

    /**
     * @return mixed
     */
    public function getCorrectAnswer()
    {
        return $this->correctAnswer;
    }

    /**
     * @param mixed $correctAnswer
     */
    public function setCorrectAnswer($correctAnswer)
    {
        $this->correctAnswer = $correctAnswer;
    }
}



//{
//    questions_en_US: [
//        {
//            index: 1,
//            question: 'What is the name for a group of lions?',
//            answers: ['pack', 'pride', 'den', 'frat'],
//            correct_answer: 'pride'
//        },
//        {
//            index: 2,
//          question: 'What is the only type of bear native to South America?',
//          answers: ['brown bear', 'kodiac', 'giant panda', 'spectacled bear'],
//          correct_answer: 'spectacled bear'
//        },
//        {
//            index: 3,
//            question: 'What type of animal is a seahorse?',
//            answers: ['crustacean', 'arachnid', 'fish', 'shell'],
//            correct_answer: 'fish'
//        },
//        {
//            index: 4,
//            question: 'What color are zebras?',
//            answers: ['white with black stripes', 'black with white stripes'],
//            correct_answer: 'black with white stripes'
//        },
//        {
//            index: 5,
//            question: 'What is the fastest water animal?',
//            answers: ['porpoise', 'sailfish', 'flying fish', 'tuna'],
//            correct_answer: 'sailfish'
//        },
//        {
//            index: 6,
//            question: 'What is the only venomous snake found in Britain?',
//            answers: ['cobra', 'coral snake', 'copperhead', 'adder'],
//            correct_answer: 'adder'
//        },
//        {
//            index: 7,
//            question: 'What is a female donkey called?',
//            answers: ['joey', 'jenny', 'janet'],
//            correct_answer: 'jenny'
//        },
//        {
//            index: 8,
//            question: 'What land mammal other than man has the longest lifespan?',
//            answers: ['blue whale', 'dolphin', 'elephant', 'orangutan'],
//            correct_answer: 'elephant'
//        },
//        {
//            index: 9,
//            question: 'Eskimos call what kind of creature a nanook?',
//            answers: ['penguin', 'narwhal', 'polar bear', 'caribou'],
//            correct_answer: 'polar bear'
//        },
//        {
//            index: 10,
//            question: 'Lupus is the Latin name for what animal?',
//            answers: ['dog', 'cat', 'wolf', 'fox'],
//            correct_answer: 'wolf'
//        }
//
//    ]
//})