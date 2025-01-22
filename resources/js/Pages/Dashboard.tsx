import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { useEffect, useState } from 'react';

// Interface definitions for type safety and better code organization
interface Plan {
	id: number;
	name: string;
	code: string;
	prefix: string;
	numbering?: string;
}

interface Brand {
	id: number;
	name: string;
	logo: string;
}

interface Coupon {
	id: number;
	code: string;
	type: string;
	name: string;
	description: string;
	start_date: string;
	end_date: string;
	redeem_at: string;
	image: string;
}

interface AvailablePlan extends Plan {
	isJoined?: boolean;
}

interface AlertMessage {
	type: 'success' | 'error';
	message: string;
}

export default function Dashboard() {
	// State management
	const [alertMessage, setAlertMessage] = useState<AlertMessage | null>(null);
	const [brands, setBrands] = useState<Brand[]>([]);
	const [selectedBrand, setSelectedBrand] = useState<number | null>(null);
	const [availablePlans, setAvailablePlans] = useState<AvailablePlan[]>([]);
	const [myPlans, setMyPlans] = useState<Plan[]>([]);
	const [coupons, setCoupons] = useState<Coupon[]>([]);
	const [filteredCoupons, setFilteredCoupons] = useState<Coupon[]>([]);
	const [loading, setLoading] = useState({
		brands: false,
		plans: false,
		myPlans: false,
		coupons: false,
	});
	const [error, setError] = useState<string | null>(null);

	// Initial data fetch
	useEffect(() => {
		fetchBrands();
		fetchMyPlans();
		fetchAllData();
	}, []);

	// Fetch all data (plans and coupons)
	const fetchAllData = async () => {
		await Promise.all([
			fetchAllPlans(),
			fetchAllCoupons(),
		]);
	};

	// Fetch brand data
	const fetchBrands = async () => {
		setLoading(prev => ({ ...prev, brands: true }));
		try {
			const response = await fetch('/api/brands');
			if (response.ok) {
				const data = await response.json();
				setBrands(data);
			} else {
				setError('Failed to load brands');
			}
		} catch (error) {
			setError('Error loading brands');
		} finally {
			setLoading(prev => ({ ...prev, brands: false }));
		}
	};

	// Fetch all available plans
	const fetchAllPlans = async () => {
		setLoading(prev => ({ ...prev, plans: true }));
		try {
			const response = await fetch('/api/available-plans');
			if (response.ok) {
				const data = await response.json();
				const plansWithJoinedStatus = data.map((plan: Plan) => ({
					...plan,
					isJoined: myPlans.some(myPlan => myPlan.id === plan.id),
				}));
				setAvailablePlans(plansWithJoinedStatus);
				setSelectedBrand(null);
			} else {
				setError('Failed to load available plans');
			}
		} catch (error) {
			setError('Error loading available plans');
		} finally {
			setLoading(prev => ({ ...prev, plans: false }));
		}
	};

	// Fetch plans for a specific brand
	const fetchBrandPlans = async (brandId: number) => {
		setLoading(prev => ({ ...prev, plans: true }));
		try {
			const response = await fetch(`/api/brands/${brandId}/plans`);
			if (response.ok) {
				const data = await response.json();
				const plansWithJoinedStatus = data.map((plan: Plan) => ({
					...plan,
					isJoined: myPlans.some(myPlan => myPlan.id === plan.id),
				}));
				setAvailablePlans(plansWithJoinedStatus);
				setSelectedBrand(brandId);
			} else {
				setError('Failed to load brand plans');
			}
		} catch (error) {
			setError('Error loading brand plans');
		} finally {
			setLoading(prev => ({ ...prev, plans: false }));
		}
	};

	// Fetch all coupons
	const fetchAllCoupons = async () => {
		setLoading(prev => ({ ...prev, coupons: true }));
		try {
			const response = await fetch('/api/coupons');
			if (response.ok) {
				const data = await response.json();
				setCoupons(data);
				setFilteredCoupons(data);
			} else {
				setError('Failed to load coupons');
			}
		} catch (error) {
			setError('Error loading coupons');
		} finally {
			setLoading(prev => ({ ...prev, coupons: false }));
		}
	};

	// Fetch coupons for a specific brand
	const fetchBrandCoupons = async (brandId: number) => {
		setLoading(prev => ({ ...prev, coupons: true }));
		try {
			const response = await fetch(`/api/brands/${brandId}/coupons`);
			if (response.ok) {
				const data = await response.json();
				setFilteredCoupons(data);
			} else {
				setError('Failed to load brand coupons');
			}
		} catch (error) {
			setError('Error loading brand coupons');
		} finally {
			setLoading(prev => ({ ...prev, coupons: false }));
		}
	};

	// Handle brand selection
	const handleBrandSelect = async (brandId: number | null) => {
		if (brandId === null) {
			await Promise.all([
				fetchAllPlans(),
				fetchAllCoupons()
			]);
		} else {
			await Promise.all([
				fetchBrandPlans(brandId),
				fetchBrandCoupons(brandId)
			]);
		}
	};

	// Fetch user's associated plans
	const fetchMyPlans = async () => {
		setLoading(prev => ({ ...prev, myPlans: true }));
		try {
			const response = await fetch('/api/my-plans');
			if (response.ok) {
				const data = await response.json();
				setMyPlans(data);
			} else {
				setError('Failed to load my plans');
			}
		} catch (error) {
			setError('Error loading my plans');
		} finally {
			setLoading(prev => ({ ...prev, myPlans: false }));
		}
	};

	// Handle plan association
	const associatePlan = async (planId: number) => {
		try {
			const response = await fetch('/api/associate-plan', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify({ planId }),
			});

			if (response.ok) {
				await fetchMyPlans();
				if (selectedBrand) {
					await fetchBrandPlans(selectedBrand);
				} else {
					await fetchAllPlans();
				}
				setAlertMessage({
					type: 'success',
					message: 'Plan associated successfully!'
				});
				setTimeout(() => setAlertMessage(null), 3000);
			} else {
				const errorData = await response.json();
				setAlertMessage({
					type: 'error',
					message: 'Failed to associate plan: ' + (errorData.message || 'Unknown error')
				});
			}
		} catch (error) {
			setAlertMessage({
				type: 'error',
				message: 'An unexpected error occurred.'
			});
		}
	};

	return (
		<AuthenticatedLayout>
			<Head title="Dashboard" />

			<div className="py-12">
				<div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
					{/* Alert Messages */}
					{alertMessage && (
						<div role="alert" className={`alert ${alertMessage.type === 'success' ? 'alert-success' : 'alert-error'
							} mb-4`}>
							<svg
								xmlns="http://www.w3.org/2000/svg"
								className="h-6 w-6 shrink-0 stroke-current"
								fill="none"
								viewBox="0 0 24 24"
							>
								{alertMessage.type === 'success' ? (
									<path
										strokeLinecap="round"
										strokeLinejoin="round"
										strokeWidth="2"
										d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
									/>
								) : (
									<path
										strokeLinecap="round"
										strokeLinejoin="round"
										strokeWidth="2"
										d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
									/>
								)}
							</svg>
							<span>{alertMessage.message}</span>
						</div>
					)}

					{error && (
						<div className="mb-4 rounded-lg bg-red-100 p-4 text-red-700">
							{error}
						</div>
					)}

					{/* My Plans Section */}
					<div className="p-6">
						<h3 className="mb-4 text-lg font-bold">My Plans</h3>
						{loading.myPlans ? (
							<div className="text-center">Loading my plans...</div>
						) : (
							<div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
								{myPlans.length > 0 ? (
									myPlans.map((plan) => (
										<div
											key={plan.id}
											className="card bg-base-100 shadow-md transition-shadow hover:shadow-lg"
										>
											<div className="card-body">
												<h4 className="card-title">{plan.name}</h4>
												<p>Code: {plan.code}</p>
												<p>Prefix: {plan.prefix}</p>
												<p>Numbering: {plan.numbering}</p>
											</div>
										</div>
									))
								) : (
									<p>You have no plans associated.</p>
								)}
							</div>
						)}
					</div>

					{/* Brand Selection */}
					<div className="mt-8 p-6">
						<h3 className="mb-4 text-lg font-bold">Select a Brand</h3>
						{loading.brands ? (
							<div className="text-center">Loading brands...</div>
						) : (
							<div className="flex flex-wrap items-center gap-6">
								<button
									onClick={() => handleBrandSelect(null)}
									className={`flex min-w-32 flex-col items-center rounded-lg p-4 transition-colors ${selectedBrand === null
											? 'bg-primary text-white'
											: 'bg-base-100 hover:bg-base-200'
										}`}
								>
									<div className="mb-2 flex h-16 w-16 items-center justify-center">
										<svg
											className="h-8 w-8"
											fill="none"
											viewBox="0 0 24 24"
											stroke="currentColor"
										>
											<path
												strokeLinecap="round"
												strokeLinejoin="round"
												strokeWidth={2}
												d="M4 6h16M4 10h16M4 14h16M4 18h16"
											/>
										</svg>
									</div>
									<span className="font-semibold">All</span>
								</button>

								{brands.map((brand) => (
									<button
										key={brand.id}
										onClick={() => handleBrandSelect(brand.id)}
										className={`flex min-w-32 flex-col items-center rounded-lg p-4 transition-colors ${selectedBrand === brand.id
												? 'bg-primary text-white'
												: 'bg-base-100 hover:bg-base-200'
											}`}
									>
										<img
											src={brand.logo}
											alt={brand.name}
											className="mb-2 h-16 w-16 object-contain"
										/>
										<span className="font-semibold">
											{brand.name}
										</span>
									</button>
								))}
							</div>
						)}
					</div>

					{/* Coupons Section */}
					<div className="mt-8 p-6">
						<h3 className="mb-4 text-lg font-bold">
							{selectedBrand === null ? 'All Available Coupons' : 'Brand Coupons'}
						</h3>
						{loading.coupons ? (
							<div className="text-center">Loading coupons...</div>
						) : (
							<div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
								{filteredCoupons.length > 0 ? (
									filteredCoupons.map((coupon) => (
										<div
											key={coupon.id}
											className="card bg-base-100 shadow-md hover:shadow-lg transition-shadow"
										>
											<div className="card-body">
												<div className="flex justify-between items-start">
													<h4 className="card-title text-primary">
														{coupon.name}
													</h4>
													<span className="badge badge-secondary">
														{coupon.type}
													</span>
												</div>
												<p className="mt-2">{coupon.description}</p>
												<div className="mt-4 p-2 bg-base-200 rounded-lg text-center">
													<span className="font-mono text-lg">
														{coupon.code}
													</span>
												</div>
												<div className="mt-4 text-sm text-gray-500">
													<p>
														Valid until:{' '}
														{new Date(
															coupon.end_date
														).toLocaleDateString()}
													</p>
													<p>Redeem at: {coupon.redeem_at}</p>
												</div>
											</div>
										</div>
									))
								) : (
									<p>
										No coupons available{' '}
										{selectedBrand !== null && 'for this brand'}
										.
									</p>
								)}
							</div>
						)}
					</div>




					{/* Available Plans Section */}
					<div className="mt-8 p-6">
						<h3 className="mb-4 text-lg font-bold">
							{selectedBrand === null ? 'All Available Plans' : 'Available Plans'}
						</h3>
						{loading.plans ? (
							<div className="text-center">Loading plans...</div>
						) : (
							<div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
								{availablePlans.length > 0 ? (
									availablePlans.map((plan) => (
										<div
											key={plan.id}
											className="card bg-base-100 shadow-md transition-shadow hover:shadow-lg"
										>
											<div className="card-body">
												<div className="flex items-start justify-between">
													<h4 className="card-title">{plan.name}</h4>
													{plan.isJoined && (
														<span className="badge badge-primary">
															Joined
														</span>
													)}
												</div>
												<p>Code: {plan.code}</p>
												<p>Prefix: {plan.prefix}</p>
												<button
													className={`btn mt-4 ${plan.isJoined
															? 'btn-disabled bg-gray-300'
															: 'btn-primary'
														}`}
													onClick={() =>
														!plan.isJoined &&
														associatePlan(plan.id)
													}
													disabled={plan.isJoined}
												>
													{plan.isJoined
														? 'Already Joined'
														: 'Associate'}
												</button>
											</div>
										</div>
									))
								) : (
									<p>
										No available plans{' '}
										{selectedBrand !== null &&
											'for this brand'}
										.
									</p>
								)}
							</div>
						)}
					</div>

				</div>
			</div>
		</AuthenticatedLayout>
	);
}
