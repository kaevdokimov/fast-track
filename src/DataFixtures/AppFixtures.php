<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Conference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        // Add admin user
        $conn = $this->em->getConnection();
        $sql = "INSERT INTO admin (id, username, roles, password) VALUES (nextval('admin_id_seq'), 'admin', '[\"ROLE_ADMIN\"]', '\$2y\$13\$7JuJcu4Aywq9pY4aPmr3t.nRA/cSLQSxPoA3YZoIz0GcsMhZkIoqu')";
        $result = $conn->executeQuery($sql);

        foreach($this->getData() as $conferenceData)
        {
            $conference = new Conference(
                $conferenceData['city'],
                $conferenceData['year'],
                $conferenceData['isInternational']
            );
            $manager->persist($conference);

            foreach($conferenceData['comments'] as $commentData)
            {
                $comment = new Comment();
                $comment->loadData(
                    $commentData[0],
                    $commentData[1],
                    $commentData[2]
                );
                $comment->setConference($conference);
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    private function getData():array
    {
        return [
            [
                'city' => 'Moscow',
                'year' => 2024,
                'isInternational' => true,
                'comments' => [
                    [
                        'Иван Бунин',
                        'Больше всех рискует тот, кто никогда не рискует',
                        'ivan.bunin@ya.ru'
                    ],
                    [
                        'Иван Тургенев',
                        'Счастье — как здоровье: когда его не замечаешь, значит, оно есть',
                        'ivan.turgenev@ya.ru'
                    ],
                    [
                        'Федор Достоевский',
                        'Общие принципы только в головах, а в жизни одни только частные случаи',
                        'fedor.dostoevskiy@ya.ru'
                    ],
                    [
                        'Федор Достоевский',
                        'Общие принципы только в головах, а в жизни одни только частные случаи',
                        'fedor.dostoevskiy@ya.ru'
                    ],
                    [
                        'Лев Толстой',
                        'Каждый мечтает изменить мир, но никто не ставит целью изменить самого себя',
                        'lev.tolstoy@ya.ru'
                    ],
                    [
                        'Александр Куприн',
                        'Ничто так на соединяет людей, как улыбка',
                        'alexandr.kuprin@ya.ru'
                    ],
                    [
                        'Антон Чехов',
                        'Попал в стаю, лай не лай, а хвостом виляй',
                        'anton.chehov@ya.ru'
                    ],
                ],
            ],
            [
                'city' => 'Санкт-Петербург',
                'year' => 2023,
                'isInternational' => true,
                'comments' => [
                    [
                        'Федор Достоевский',
                        'Красота спасет мир',
                        'fedor.dostoevskiy@ya.ru'
                    ],
                    [
                        'Лев Толстой',
                        'И нет величия там, где нет простоты, добра и правды.',
                        'lev.tolstoy@ya.ru'
                    ],
                    [
                        'Александр Куприн',
                        'Смутное влечение сердца никогда не ошибается в своих быстрых тайных предчувствиях',
                        'alexandr.kuprin@ya.ru'
                    ],
                    [
                        'Антон Чехов',
                        'Жизнь дается один раз, и хочется прожить ее бодро, осмысленно, красиво',
                        'anton.chehov@ya.ru'
                    ],
                ]
            ],
            [
                'city' => 'Нижний Новгород',
                'year' => 2024,
                'isInternational' => false,
                'comments' => [
                    [
                        'Лев Толстой',
                        'Все разнообразие, вся прелесть, вся красота жизни слагается из тени и света',
                        'lev.tolstoy@ya.ru'
                    ],
                    [
                        'Федор Достоевский',
                        'Хорошее время не с неба падает, а мы его делаем…',
                        'fedor.dostoevskiy@ya.ru'
                    ],
                    [
                        'Александр Куприн',
                        'Никогда не отчаивайтесь. Иногда всё складывается так плохо, хоть вешайся, а — глядь — завтра жизнь круто переменилась',
                        'alexandr.kuprin@ya.ru'
                    ],
                    [
                        'Антон Чехов',
                        'На боль я отвечаю криком и слезами, на подлость — негодованием, на мерзость — отвращением. По-моему, это, собственно, и называется жизнью.',
                        'anton.chehov@ya.ru'
                    ],
                ]
            ],
        ];
    }
}
