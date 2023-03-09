import React from 'react'
import {
	SkeletonPage,
	Layout,
	Card,
	SkeletonBodyText,
	TextContainer,
	SkeletonDisplayText,
	SkeletonThumbnail
} from '@shopify/polaris'

const count = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]

const SkeletonShopList = () => {
	return (
		<SkeletonPage primaryAction fullWidth>
			<Card sectioned>
				<Card.Section>
					<div
						style={{
							display: 'flex',
							alignItems: 'center',
							justifyContent: 'space-between'
						}}>
						<div
							style={{
								display: 'flex',
								alignItems: 'center',
								gap: '10px'
							}}>
							<div
								style={{
									width: '60px'
								}}>
								<SkeletonDisplayText size='small' />
							</div>
							<div
								style={{
									width: '60px'
								}}>
								<SkeletonDisplayText size='small' />
							</div>
							<div
								style={{
									width: '60px'
								}}>
								<SkeletonDisplayText size='small' />
							</div>
							<div
								style={{
									height: '28px',
									width: '28px',
									overflow: 'hidden',
									borderRadius: '3px'
								}}>
								<SkeletonThumbnail size='small' />
							</div>
						</div>

						<div
							style={{
								display: 'flex',
								alignItems: 'center',
								gap: '10px'
							}}>
							<div
								style={{
									width: '40px'
								}}>
								<SkeletonDisplayText size='small' />
							</div>
							<div
								style={{
									height: '28px',
									width: '28px',
									overflow: 'hidden',
									borderRadius: '3px'
								}}>
								<SkeletonThumbnail size='small' />
							</div>
						</div>
					</div>
				</Card.Section>
				{count &&
					count.map((el) => (
						<Card.Section key={el}>
							<div
								style={{
									display: 'flex',
									alignItems: 'center',
									justifyContent: 'space-between'
								}}>
								<div
									style={{
										width: '100%',
										display: 'flex',
										alignItems: 'center',
										justifyContent: 'space-between'
									}}>
									<SkeletonThumbnail size='extraSmall' />
									<div style={{width: '150px'}}>
										<SkeletonBodyText lines={1} />
									</div>
									<div style={{width: '150px'}}>
										<SkeletonBodyText lines={1} />
									</div>
									<div style={{width: '150px'}}>
										<SkeletonBodyText lines={1} />
									</div>
									<div style={{width: '150px'}}>
										<SkeletonBodyText lines={1} />
									</div>
									<div style={{width: '150px'}}>
										<SkeletonBodyText lines={1} />
									</div>
									<div style={{width: '150px'}}>
										<SkeletonBodyText lines={1} />
									</div>
									<div style={{width: '150px'}}>
										<SkeletonBodyText lines={1} />
									</div>
								</div>
							</div>
						</Card.Section>
					))}
			</Card>
		</SkeletonPage>
	)
}

export default SkeletonShopList
