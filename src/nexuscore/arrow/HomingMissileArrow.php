<?php
namespace nexuscore\arrow;
use pocketmine\entity\projectile\Arrow;
use pocketmine\entity\{
	Entity,
	Living,
    Location
};
use pocketmine\world\World;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\Explosion;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\world\sound\ArrowHitSound;

final class HomingMissileArrow extends Arrow{
    protected $gravity = 0;
	protected $damage = 3.0;
	protected $punchKnockback = 2.0;
	private $shooter;
	private $level;

    
	public function __construct(Location $location, ?CompoundTag $nbt = null, ?Entity $entity = null, bool $critical = false , ?World $level = null){
		parent::__construct(
			$location,
			$entity,
			$critical,
			$nbt
		);
		if($entity === null) return;
		//$this->setMotion($entity->getDirectionVector()->normalize()->multiply(0.2));
		$this->shooter = $entity;
		$this->level = $level;
	}

	public function entityBaseTick(int $tick = 1):bool{
 	  $newTarget = $this->level->getNearestEntity($this->getLocation(), 50.0, Living::class);
          if($newTarget instanceof Living){
            if($this->shooter === null){
	      $currentTarget = null;
	    }else{
              if($this->shooter->getId() !== $newTarget->getId()){
	        $currentTarget = $newTarget;
	      }else{
	        $currentTarget = null;
	      }
	    }
	  }else{
            $currentTarget = null;
          }

	  if($currentTarget !== null){
		$vector = $currentTarget->getPosition()->add(0, $currentTarget->getEyeHeight() / 2, 0)->subtract($this->getLocation()->getX(),$this->getLocation()->getY(),$this->getLocation()->getZ())->divide(2.0);

		$distance = $vector->lengthSquared();
		if($distance < 1){
		  $diff = $vector->normalize()->multiply(5 * (1 - sqrt($distance)) ** 2);
		  $this->motion->x += $diff->x;
		  $this->motion->y += $diff->y;
		  $this->motion->z += $diff->z;
		}
	  }
		if($this->closed){
			return false;
		}
	
		$hasUpdate = parent::entityBaseTick($tick);
	
		if($this->blockHit !== null){
			$this->collideTicks += $tick;
			if($this->collideTicks > 1200){
				$this->flagForDespawn();
				$hasUpdate = true;
			}
		}else{
			$this->collideTicks = 0;
		}
	
		return $hasUpdate;
	}
	
	protected function onHit(ProjectileHitEvent $event) : void{
		$this->setCritical(false);
		$this->broadcastSound(new ArrowHitSound());
		$explosion = new Explosion($event->getEntity()->location->asLocation(), 3);
		$explosion->explodeB();
	}
}
