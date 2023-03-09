import React from 'react'
import {Card, Button} from '@shopify/polaris'
import '../../assets/plans/TariffPlanComponent.css'

const TariffCard = ({
	title,
	description,
	price,
	arrayOfBenefits,
	handler,
	isPopular = false
}) => {
	return (
		<div className={`card ${isPopular ? 'card__popular-wrapper' : ''}`}>
			{isPopular && (
				<div className='card__popular--top'>
					<p className='card__title'>Most Popular</p>
				</div>
			)}
			<Card>
				<div
					className={`card__inner ${
						isPopular ? 'card__popular' : ''
					}`}>
					<div className='card__headings'>
						{title && (
							<h2 className='card__headings--title'>{title}</h2>
						)}
						{description && (
							<p className='card__text'>{description}</p>
						)}
					</div>
					<div className='card__price'>
						<span className='card__price--value'>
							{price}
							<span className='card__text'>/mo</span>
						</span>
						<p className='card__text'>billed yearly</p>
					</div>
					<div className='card__benefits'>
						<p className='card__title'>
							What’s included on {title}
						</p>
						{arrayOfBenefits && (
							<ul className='card__benefits--list'>
								{arrayOfBenefits.map((el) => (
									<li
										className='card__benefits--list-item'
										key={el}>
										<svg
											width='21'
											height='20'
											viewBox='0 0 21 20'
											fill='none'
											xmlns='http://www.w3.org/2000/svg'>
											<path
												fill-rule='evenodd'
												clip-rule='evenodd'
												d='M7.79325 14.7066L4.79325 11.7066C4.40225 11.3156 4.40225 10.6836 4.79325 10.2926C5.18425 9.90159 5.81625 9.90159 6.20725 10.2926L8.44325 12.5286L14.7413 5.34959C15.1012 4.92959 15.7323 4.88159 16.1503 5.24059C16.5703 5.60059 16.6192 6.23059 16.2592 6.64959L9.25925 14.6496C9.07825 14.8616 8.81625 14.9876 8.53825 14.9986C8.23525 14.9996 7.98025 14.8946 7.79325 14.7066Z'
												fill='#007F5F'
											/>
										</svg>
										{el}
									</li>
								))}
							</ul>
						)}
					</div>
					<Button primary fullWidth onClick={() => handler()}>
						Buy Plan
					</Button>
				</div>
			</Card>
		</div>
	)
}

export default TariffCard
