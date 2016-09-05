<?php

namespace AppBundle\EntityRepository;

use Customer\Customer\CustomerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Class CompanyRepository
 *
 * @package AppBundle\EntityRepository
 */
class CompanyRepository extends EntityRepository
{
    /**
     * Get by ids
     *
     * @param CustomerInterface $customer
     * @param array $points
     * @return mixed
     */
    public function areOwnedOrShared(CustomerInterface $customer, array $points)
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('AppBundle:Company', 'c');
        $rsm->addFieldResult('c', 'id', 'id');
        $rsm->addFieldResult('c', 'external_id', 'externalId');
        $rsm->addFieldResult('c', 'social_reason', 'socialReason');
        $rsm->addFieldResult('c', 'social_object', 'socialObject');
        $rsm->addFieldResult('c', 'latitude', 'latitude');
        $rsm->addFieldResult('c', 'longitude', 'longitude');
        $rsm->addFieldResult('c', 'cif', 'cif');
        $rsm->addFieldResult('c', 'address', 'address');
        $rsm->addFieldResult('c', 'phone_number', 'phoneNumber');
        $rsm->addFieldResult('c', 'created', 'created');
        $rsm->addFieldResult('c', 'updated', 'updated');
        $rsm->addFieldResult('c', 'deleted_at', 'deletedAt');

        $query = $this->getEntityManager()
            ->createNativeQuery('
            SELECT
              DISTINCT
              c4_.id            AS id_0,
              c4_.external_id   AS external_id_1,
              c4_.social_reason AS social_reason_2,
              c4_.social_object AS social_object_3,
              c4_.latitude      AS latitude_4,
              c4_.longitude     AS longitude_5,
              c4_.cif           AS cif_6,
              c4_.address       AS address_7,
              c4_.phone_number  AS phone_number_8,
              c4_.created       AS created_9,
              c4_.updated       AS updated_10,
              c4_.deleted_at    AS deleted_at_11
            FROM customer_view_company c1_,
              shared_static_list s2_
              INNER JOIN static_list s3_ ON s2_.static_list = s3_.id
              RIGHT JOIN static_list_company s5_ ON s3_.id = s5_.static_list
              INNER JOIN company c4_ ON c4_.id = s5_.company AND (c4_.deleted_at IS NULL)
            WHERE (c4_.id IN (:ids)
                   AND (
                     (c4_.id = c1_.company AND c1_.customer = :customerId)
                     OR
                     (s2_.customer = :customerId)))
                  AND (c1_.deleted_at IS NULL)
                  AND (s2_.deleted_at IS NULL)
                  AND (s3_.deleted_at IS NULL)',
                $rsm);

        $query->setParameter('ids', implode(',', $points));
        $query->setParameter('customerId', $customer->getId());

        $companies = $query->getResult();

        $ids = array_reduce(
            $companies,
            function ($carry, $current) {
                $carry[] = $current['company'];

                return $carry;
            },
            []
        );

        if (count($companies) !== count($points) && $points != $ids) {
            return false;
        }

        return true;
    }
}