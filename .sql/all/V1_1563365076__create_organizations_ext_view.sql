CREATE OR REPLACE VIEW _organizations_ext as
  (SELECT o.*, count(s.id) as shop_cnt, IF(legal_name > '', legal_name, boss_full_name) as title
   FROM organizations o
          LEFT JOIN shops s
                    ON (o.id = s.organization_id)
   GROUP BY o.id);
