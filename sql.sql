

select * from company_brand; 


select * from plan_user;

show tables;

select * from brand_plan;


SELECT bp.brand_id
FROM plan_user pu
JOIN brand_plan bp ON pu.plan_id = bp.plan_id
WHERE pu.plan_id = 1;

select id, name, company_id from brands;


