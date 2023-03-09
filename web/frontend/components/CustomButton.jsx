import React, {useState} from 'react'

const CustomButton = ({el, handleOpenAcceptModal}) => {
	const [isHover, setIsHover] = useState(false)

	const handleEnter = () => {
		setIsHover(true)
	}

	const handleLeave = () => {
		setIsHover(false)
	}
	return (
		<button
			style={{
				background: isHover ? '#202123' : '#2f3133',
				padding: '3px 12px',
				color: '#e3e5e7',
				fontSize: '14px',
				borderRadius: '4px',
				border: '1px solid rgba(80, 83, 86, 1)',
				cursor: 'pointer'
			}}
			onMouseEnter={() => {
				handleEnter()
			}}
			onMouseLeave={() => {
				handleLeave()
			}}
			onClick={() => handleOpenAcceptModal(el.content)}>
			{el.content}
		</button>
	)
}

export default CustomButton
