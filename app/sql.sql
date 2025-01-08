SELECT 
    pp.id AS pool_plan_id,
    pp.pool_id,
    pp.plan_id,
    p.name AS plan_name,
		pools.status,
    pools.coupon_limit,
    pools.coupons_used
FROM 
    pool_plan pp
JOIN 
    plans p
ON 
    pp.plan_id = p.id
JOIN 
    pools
ON 
    pp.pool_id = pools.id
WHERE 
    pp.plan_id = 1;

select * from pool_plan;

